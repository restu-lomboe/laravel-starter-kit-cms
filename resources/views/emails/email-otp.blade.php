<x-mail::message>
<div style="text-align:center; margin-bottom: 20px;">
<div style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; background:#f59e0b; border-radius:12px; color:#fff; font-weight:700; font-size:20px;">A</div>
<div style="font-size:13px; color:#6b7280; margin-top:6px; letter-spacing:0.08em; text-transform:uppercase;">Anchor HR</div>
</div>

# Your verification code

Use the code below to sign in to your **Anchor HR** workspace. This code expires in **{{ $expiresMinutes }} minutes**.

<div style="background:#fefce8; border:1px solid #fde68a; border-radius:12px; padding:18px; text-align:center; margin:18px 0;">
<div style="font-size:11px; letter-spacing:0.18em; text-transform:uppercase; color:#92400e; font-weight:600; margin-bottom:6px;">One-time code</div>
<div style="font-size:32px; letter-spacing:0.32em; font-weight:800; color:#111827; font-family:ui-monospace, SFMono-Regular, monospace;">{{ $code }}</div>
<div style="font-size:11px; color:#92400e; margin-top:8px;">Valid for {{ $expiresMinutes }} minutes • One-time use only</div>
</div>

> **Didn’t request this code?** Someone may have typed your email by mistake. You can safely ignore this email — your account remains secure.

Thanks,<br>
**{{ config('app.name') }} Team**

<div style="border-top:1px solid #e5e7eb; margin-top:24px; padding-top:16px; font-size:11px; color:#9ca3af; text-align:center;">
This is an automated message — please don’t reply. Need help? Contact your HR administrator.
</div>
</x-mail::message>
