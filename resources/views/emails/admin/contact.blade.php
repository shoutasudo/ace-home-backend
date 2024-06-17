<div style="font-weight: 700; font-size: 20px;">
    以下、お問い合わせを受信しました。
</div>
<div style="width: 90vw; margin-top: 20px; font-size: 16px; color: #222">
    <div class="grid" style="display: grid; grid-template-columns: 160px 1fr; gap: 8px;">
        <div>
            【お問い合わせ内容】
        </div>
        <div style="padding-left: 8px;">
            @switch($attributes['contactType'])
                @case(1)
                    ACE HOMEについて
                    @break
                @case(2)
                    物件について
                    @break
                @case(3)
                    リフォームについて
                    @break
                @case(4)
                    売買について
                    @break
                @case(5)
                    求人について
                    @break
                @case(6)
                    その他のお問合せについて
                    @break
                @default
            @endswitch
        </div>
        <div>
            【お名前】
        </div>
        <div style="padding-left: 8px;">
            {{ $attributes['name'] }}
        </div>
        <div>
            【会社名】
        </div>
        <div style="padding-left: 8px;">
            {{ $attributes['companyName'] }}
        </div>
        <div>
            【電話番号】
        </div>
        <div style="padding-left: 8px;">
            {{ $attributes['telNumber'] }}
        </div>
        <div>
            【メールアドレス】
        </div>
        <div style="padding-left: 8px;">
            {{ $attributes['email'] }}
        </div>
        <div>
            【お問い合わせ本文】
        </div>
        <pre style="margin: 0; padding-left: 8px; white-space: pre-line; font-family: sans-serif;">
            {{ $attributes['content'] }}
        </pre>
    </div>
</div>
    <div style="margin: 12px 0;">
    ====================<br/>
    内容を確認のうえ、メール又は電話にて回答・対応してください。
</div>

<style>
    @media (max-width: 600px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
