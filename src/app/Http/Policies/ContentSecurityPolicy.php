<?php

namespace App\Http\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class ContentSecurityPolicy extends Basic
{
    public function configure()
    {
        // parent::configure();

        if(request()->is('admin/*')){
            $this->addNonceForDirective(Directive::STYLE);
        }

        $this
        //start of basic policy
        ->addDirective(Directive::BASE, Keyword::SELF)
        ->addDirective(Directive::CONNECT, Keyword::SELF)
        ->addDirective(Directive::DEFAULT, Keyword::SELF)
        ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
        ->addDirective(Directive::IMG, Keyword::SELF)
        ->addDirective(Directive::MEDIA, Keyword::SELF)
        ->addDirective(Directive::OBJECT, Keyword::NONE)
        ->addDirective(Directive::SCRIPT, Keyword::SELF)
        ->addDirective(Directive::STYLE, Keyword::SELF)
        ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
        ->addDirective(Directive::FRAME, Keyword::SELF)
        ->addDirective(Directive::FONT, Keyword::SELF)
        ->addNonceForDirective(Directive::SCRIPT);

        if(request()->is('admin/document/view/*') || request()->is('admin/document/trash/view/*') || request()->is('content/document/*')){
            $this
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_EVAL);
        }

        //end of basic policy

        //start of custom policy
        $this
        //start of
        ->addDirective(Directive::IMG, 'data:')
        ->addDirective(Directive::IMG, 'blob:')
        ->addDirective(Directive::FONT, 'data:') //remove as this and above belongs for development template of welcome page

        //start of artibot
        ->addDirective(Directive::SCRIPT, 'app.artibot.ai')
        ->addDirective(Directive::FRAME, 'app.artibot.ai')
        ->addDirective(Directive::SCRIPT, 'prod.artibotcdn.com')
        ->addDirective(Directive::CONNECT, 'api.artibot.ai')
        ->addDirective(Directive::CONNECT, 'api-cdn.prod-aws.artibot.ai')
        ->addDirective(Directive::IMG, 's3.amazonaws.com')

        //start of common
        ->addDirective(Directive::IMG, 'i3.ytimg.com')
        ->addDirective(Directive::IMG, 'i.vimeocdn.com')
        ->addDirective(Directive::IMG, 'vumbnail.com')
        ->addDirective(Directive::FONT, 'use.fontawesome.com')
        ->addDirective(Directive::FONT, 'at.alicdn.com')
        ->addDirective(Directive::FONT, 'fonts.gstatic.com')
        ->addDirective(Directive::SCRIPT, 'player.vimeo.com')
        ->addDirective(Directive::SCRIPT, 'cdnjs.cloudflare.com')
        ->addDirective(Directive::STYLE, 'use.fontawesome.com')
        ->addDirective(Directive::STYLE, 'fonts.googleapis.com')
        ->addDirective(Directive::FRAME, 'www.google.com')
        ->addDirective(Directive::FRAME, 'player.vimeo.com')
        ->addDirective(Directive::FRAME, 'www.youtube.com')
        ->addDirective(Directive::CONNECT, 'cdn.plyr.io');
    }

}

?>
