<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class ContentReportController extends Controller
{
    protected $model;
    protected $with_model;

    public function __construct(string $model, string $with_model)
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);

        $this->initializeSubject($model, $with_model);
    }

    protected function initializeSubject($model, $with_model): static
    {

        $this->model = $model;
        $this->with_model = $with_model;

        return $this;
    }

    protected function query()
    {
        return $this->model::with([$this->with_model,'User'])
                ->where(function($query){
                    $query->whereHas($this->with_model, function($q) {
                        $q->whereNull('deleted_at');
                    })
                    ->whereHas('User', function($q) {
                        $q->whereNull('deleted_at');
                    });
                })
                ->orderBy('id', 'DESC');
    }

    protected function view_report_base(string $view) {
        $data = $this->query();

        if (request()->has('filter') && request()->input('filter')!='all') {
            $filter = request()->input('filter');
            $data->where('status',$filter);
        }

        if (request()->has('search')) {
            $search = request()->input('search');
            $data->where(function($query)  use ($search){
                $query->whereHas($this->with_model, function($q)  use ($search){
                    $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('uuid', 'like', '%' . $search . '%');
                })
                ->orWhereHas('User', function($q)  use ($search){
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        $data = $data->paginate(10);
        return view($view)
        ->with('country', $data);
    }

    protected function delete_report_base(string $redirect, $id){
        $data = $this->query()
        ->findOrFail($id);
        $data->forceDelete();
        return redirect()->intended(route($redirect))->with('success_status', 'Data Deleted successfully.');
    }

    protected function toggle_report_base($id){
        if (request()->has('status')) {
            $data = $this->query()
                ->findOrFail($id);
            switch (request()->input('status')) {
                case '0':
                case '1':
                case '2':
                    # code...
                    $data->status = request()->input('status');
                    $data->admin_id = Auth::user()->id;
                    $data->save();
                    return redirect()->intended(URL::previous())->with('success_status', 'Status updated successfully.');
                    break;

                default:
                    # code...
                    return redirect()->intended(URL::previous())->with('error_status', 'Invalid status found.');
                    break;
            }
        }else{
            return redirect()->intended(URL::previous())->with('error_status', 'No status found.');
        }

    }

    protected function display_report_base(string $view, $id) {
        $data = $this->query()
        ->findOrFail($id);
        return view($view)->with('country',$data);
    }

}
