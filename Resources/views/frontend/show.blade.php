@extends('layouts.master')

@section('meta')
    <meta name="description" content="{!! $post->summary !!}">
    <!-- Schema.org para Google+ -->
    <meta itemprop="name" content="{{$post->name}}">
    <meta itemprop="description" content="{!! $post->summary !!}">
    <meta itemprop="image" content=" {{url($post->options->mainimage) }}">
    <!-- Open Graph para Facebook-->
    <meta property="og:title" content="{{$post->name}}"/>
    <meta property="og:type" content="articulo"/>
    <meta property="og:url" content="{{url($post->slug)}}"/>
    <meta property="og:image" content="{{url($post->options->mainimage)}}"/>
    <meta property="og:description" content="{!! $post->summary !!}"/>
    <meta property="og:site_name" content="{{Setting::get('core::site-name') }}"/>
    <meta property="og:locale" content="{{locale().'_CO'}}">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ Setting::get('core::site-name') }}">
    <meta name="twitter:title" content="{{$post->name}}">
    <meta name="twitter:description" content="{!! $post->summary !!}">
    <meta name="twitter:creator" content="">
    <meta name="twitter:image:src" content="{{url($post->options->mainimage)}}">

@stop

@section('title')
    {{ $post->title }} | @parent
@stop

@section('content')
    <div class="page blog single single-{{$category->slug}} single-{{$category->id}}">
        <div class="container" id="body-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-9 column1">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bgimg">
                                @if(isset($post->options->mainimage)&&!empty($post->options->mainimage))
                                    <img class="image img-responsive" src="{{url($post->options->mainimage)}}"
                                         alt="{{$post->title}}"/>
                                @else
                                    <img class="image img-responsive"
                                         src="{{url('modules/ishoppingcart/img/post/default.jpg')}}" alt="{{$post->title}}"/>
                                @endif
                            </div>
                        </div>
                    </div>

                    

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="share-box">
                                <span>Compartir:</span>
                                <div class="share-box-wrap">
                                    <div class="share-box">
                                        <ul class="social-share">
                                            <li class="facebook_share">
                                                <a onclick="window.open('http://www.facebook.com/sharer.php?u={{$post->url }}','Facebook','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+'')">
                                                    <div class="share-item-icon"><i class="fa fa-facebook "
                                                                                    title="Facebook"></i></div>
                                                </a>
                                            </li>
                                            <li class="twitter_share">
                                                <a onclick="window.open('http://twitter.com/share?url={{ $post->url }}','Twitter share','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+'')">
                                                    <div class="share-item-icon"><i class="fa fa-twitter "
                                                                                    title="Twitter"></i></div>
                                                </a>
                                            </li>
                                            <li class="gplus_share">
                                                <a onclick="window.open('https://plus.google.com/share?url={{ $post->url }}','Google plus','width=585,height=666,left='+(screen.availWidth/2-292)+',top='+(screen.availHeight/2-333)+'')">
                                                    <div class="share-item-icon"><i class="fa fa-google-plus "
                                                                                    title="Google Plus"></i></div>
                                                </a>
                                            </li>
                                            <li class="pinterest_share">
                                                <a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
                                                    <div class="share-item-icon"><i class="fa fa-pinterest "
                                                                                    title="Pinterest"></i></div>
                                                </a>
                                            </li>
                                            <li class="linkedin_share">
                                                <a onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url={{ $post->url }}','Linkedin','width=863,height=500,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+'')">
                                                    <div class="share-item-icon"><i class="fa fa-linkedin "
                                                                                    title="Linkedin"></i></div>
                                                </a>
                                            </li>
                                            <li class="whatsapp_share">
                                                <!-- WhatsApp Share Button-->
                                                <a href="whatsapp://send?text={{ $post->url }}"
                                                   data-action="share/whatsapp/share">
                                                    <div class="share-item-icon"><span class="fa-stack"><i
                                                                    class="fa fa-square fa-stack-2x"></i><i
                                                                    class="fa fa-whatsapp fa-stack-1x"></i></span></div>
                                                </a>
                                                <!-- /WhatsApp Share Button  -->
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="facebook-comments">

                        <div class="fb-comments"
                             data-href="{{url($post->url)}}"
                             data-numposts="5" data-width="100%">
                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-3 column2">
                    <div class="sidebar-revista">
                        <div class="cate">
                            <h3>Categorias</h3>

                            <div class="listado-cat">
                                <ul>
                                    @php
                                        $categories=get_categories();
                                    @endphp

                                    @if(isset($categories))
                                        @foreach($categories as $index=>$category)
                                            <li><a href="{{url($category->slug)}}">{{$category->title}}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <div id="fb-root"></div>
                <script>(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.8";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                </script>
            </div>
        </div>
    </div>
@stop
