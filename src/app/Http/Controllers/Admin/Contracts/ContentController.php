<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;
use Illuminate\Database\Eloquent\Builder;

class ContentController extends Controller
{
    protected $model;

    public function __construct(string $model)
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);

        $this->initializeSubject($model);
    }

    protected function initializeSubject($model): static
    {

        $this->model = $model;

        return $this;
    }

    protected function get_tags()
    {
        $tags = $this->model::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        return (new TagService)->get_tags($tags);
    }

    protected function create_base(string $view) {
        $tags_data = $this->get_tags();
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];
        return view($view)->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    protected function edit_base(string $view, $id) {
        $data = $this->model::findOrFail($id);
        $tags_data = $this->get_tags();
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];
        return view($view)->with('country',$data)->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    protected function restore_trash_base(string $redirect, $id){
        $data = $this->model::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $data->restore();
        return redirect()->intended(route($redirect))->with('success_status', 'Data Restored successfully.');
    }

    public function restore_all_trash_base(string $redirect){
        $this->model::withTrashed()->whereNotNull('deleted_at')->restore();
        return redirect()->intended(route($redirect))->with('success_status', 'Data Restored successfully.');
    }

    public function delete_base(string $redirect, $id){
        $data = $this->model::findOrFail($id);
        $data->delete();
        return redirect()->intended(route($redirect))->with('success_status', 'Data Deleted successfully.');
    }

    public function display_base(string $view, $id) {
        $data = $this->model::findOrFail($id);
        return view($view)->with('country',$data);
    }

    public function display_trash_base(string $view, $id) {
        $data = $this->model::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        return view($view)->with('country',$data);
    }

    public function view_base(string $view) {
        $query = $this->model::with(['User'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view($view)->with('country', $data);
    }

    public function view_trash_base(string $view) {
        $query = $this->model::withTrashed()->whereNotNull('deleted_at')->with(['User'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view($view)->with('country', $data);
    }

    private function pagination_query(Builder $query): Builder
    {
        if (request()->has('search')) {
            $search = request()->input('search');
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('year', 'like', '%' . $search . '%')
                ->orWhere('deity', 'like', '%' . $search . '%')
                ->orWhere('version', 'like', '%' . $search . '%')
                ->orWhere('tags', 'like', '%' . $search . '%')
                ->orWhere('uuid', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

}
