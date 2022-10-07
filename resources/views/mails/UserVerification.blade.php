<div style="background: #f6c23e;padding:10%">
    <div
        style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:16px;line-height:1.5em;background: #fff;padding:15px">
        <h3>Hello!</h3>
        <p>
            Please click the button below to verify your email address.
        </p>
        <p style="text-align: center;padding:20px">
            <a href="{{ $url }}" style="background: #333;color:#fff;text-decoration: none;padding:15px 10px">Verify
                Email Address</a>
        </p>
        <p>
            If you did not create an account, no further action is required.
        </p>
        <p>
            Regards
        </p>

        <p style="background: #eee;font-size:13px;padding:15px">
            If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your
            web
            browser: {{ $url }}
        </p>
    </div>
