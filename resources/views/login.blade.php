@extends('layouts.master')
@section('content')
  @if (count($errors) > 0)
  <blockquote>
      <ul>
          @foreach ($errors as $k=>$error)
              <li>
                @if ($k==0)
                  <i class="material-icons">warning</i>
                @endif
                {{ $error }}
              </li>
          @endforeach
      </ul>
  </blockquote>
  @endif
  <div class="col s12 m2 center-align">
    <p class="z-depth-3" style="padding: 15px 0"># Sign-in with Google! using an email from the following allowed domains: {{$allowed_domains}}<br/>
      <a href="/socialite/google/login">
        <img src="/images/btn_google_signin_dark_normal_web.png">
      </a>
    </p>
  </div>
@stop
