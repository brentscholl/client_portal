@component('mail::message')
    # Title 1
    ## Title 2
    ### Title 3

    The body of your message.

    @component('mail::button', ['url' => '', 'color' => 'primary'])
        Button Text
    @endcomponent

    @component('mail::panel')
        This is the panel content.
    @endcomponent

    @component('mail::table')
        | Laravel       | Table         | Example  |
        | ------------- |:-------------:| --------:|
        | Col 2 is      | Centered      | $10      |
        | Col 3 is      | Right-Aligned | $20      |
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}

    @component('mail::subcopy')
        Sub copy
    @endcomponent
@endcomponent
