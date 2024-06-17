<div style="margin-top: 10px; font-weight: 700; font-size: 20px; color: #323; text-align: center;">
    {{ $attributes['name'] }} <span style="font-size: 16px;">様</span>
</div>
<div style="margin-top: 48px; font-size: 16px; text-align: center;">
    <div>
        この度は ACE HOME にお問い合わせいただきまして誠にありがとうございます。
    </div>
    <div style="padding-top: 6px;">
        下記の内容で受け付けました。
    </div>
</div>
{{-- contact contents --}}
<div style="margin-top: 36px; font-size: 14px; text-align: center;">
    * ------------------------- *
</div>
<div style="width: 90vw; margin-top: 8px; margin-left: 4px; font-size: 16px; color: #3a3a3a">
    <div class="contents-grid" style="display: grid; grid-template-columns: 160px 1fr; gap: 8px;">
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
<div style="margin-top: 8px; font-size: 14px; text-align: center;">
    * ------------------------- *
</div>
{{-- ad. --}}
<div style="margin-top: 28px; text-align: center; font-size: 16px;">
    担当より２〜３営業日以内にメール又は電話でいたしますのでお待ち下さい。
</div>
{{-- company info. --}}
<div class="company-info-font" style="margin-top: 48px; color: #333; font-size: 16px;">
    <div>
        ┏ ──────────────────── ┓
    </div>
    <div style="font-weight: 600; margin-top:2px;">
        株式会社ACE HOME
    </div>
    <div style="margin-top:2px;">
        〒 653-0812
    </div>
    <div style="margin-top:2px;">
        兵庫県神戸市長田区長田町５丁目３番２号
    </div>
    <div style="margin-top:2px;">
        TEL : 090-1234-5678
    </div>
    <div style="margin-top:2px;">
        E-mail: <a href=`mailto:{{ $mail }}`>{{ $mail }}</a>
    </div>
    <div style="margin-top:2px;">
        ┗ ──────────────────── ┛
    </div>
</div>

<style>
    @media (max-width: 600px) {
        .contents-grid {
            grid-template-columns: 1fr !important;
        }
        .company-info-font {
            font-size: 12px !important;
        }
    }
</style>
