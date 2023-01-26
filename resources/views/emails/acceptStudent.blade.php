@component('mail::message')
# @lang('mail.student.Introduction' , ['name' => $student->name])

@lang('mail.student.body')

@component('mail::button', ['url' => route('student.home')])
@lang('mail.student.Button')
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
