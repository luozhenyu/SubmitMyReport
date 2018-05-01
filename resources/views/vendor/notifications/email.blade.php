@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        @if ($level == 'error')
            # @lang('糟糕！')
        @else
            # @lang('您好！')
        @endif
    @endif

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        {{ $line }}

    @endforeach

    {{-- Action Button --}}
    @isset($actionText)
        <?php
        switch ($level) {
            case 'success':
                $color = 'green';
                break;
            case 'error':
                $color = 'red';
                break;
            default:
                $color = 'blue';
        }
        ?>
        @component('mail::button', ['url' => $actionUrl, 'color' => $color])
            {{ $actionText }}
        @endcomponent
    @endisset

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
        {{ $line }}

    @endforeach

    {{-- Salutation --}}
    @if (! empty($salutation))
        {{ $salutation }}
    @else
        @lang('诚挚的问候'),<br>{{ config('app.name') }}
    @endif

    {{-- Subcopy --}}
    @isset($actionText)
        @component('mail::subcopy')
            @lang(
                "如果您点击 \":actionText\" 按钮时遇到问题, 请手动复制打开以下URL\n".
                '到您的浏览器中: [:actionURL](:actionURL)',
                [
                    'actionText' => $actionText,
                    'actionURL' => $actionUrl
                ]
            )
        @endcomponent
    @endisset
@endcomponent
