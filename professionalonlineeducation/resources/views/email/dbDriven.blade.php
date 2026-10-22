

@php
    $user_name = $project->user->FullName;

    $body = $template->body;
    if (isset($isClient) && $isClient == true){
        $projectUrl = 'client_project_view';
    }else{
        $projectUrl = 'agent_project_view';
    }

    $body = str_replace('[CLIENT]',$user_name,$body);
    $body = str_replace('[PROJECT-URL-ADMIN]',url(config('settings.project_url_admin').$project->id),$body);
    $body = str_replace('[PROJECT-URL]',url(route($projectUrl,$project->id)),$body);
    if (isset($msg)){
    $body = str_replace('[MESSAGE]',$msg->message,$body);
    $body = str_replace('[MESSAGE-URL]',$msg->id,$body);
    }
@endphp
{!! $body  !!}
