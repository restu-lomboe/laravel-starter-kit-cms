<x-mail::message>
<div style="text-align:center; margin-bottom: 20px;">
<div style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; background:#f59e0b; border-radius:12px; color:#fff; font-weight:700; font-size:20px;">A</div>
<div style="font-size:13px; color:#6b7280; margin-top:6px; letter-spacing:0.08em; text-transform:uppercase;">Anchor HR</div>
</div>

# Magic sign-in link

Hello,

Click the button below to securely sign in to your **Anchor HR** workspace. This link is valid for **{{ $expiresMinutes }} minutes** and can only be used once.

<x-mail::button :url="$url" color="primary">
Sign in to Anchor HR
</x-mail::button>

> **Security tip:** If you didn’t request this link, you can safely ignore this email — your account remains secure.

If the button doesn’t work, copy and paste this link into your browser:
<div style="word-break: break-all; font-size:12px; color:#6b7280; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px; margin:12px 0;">{{ $url }}</div>

Thanks,<br>
**{{ config('app.name') }} Team**

<div style="border-top:1px solid #e5e7eb; margin-top:24px; padding-top:16px; font-size:11px; color:#9ca3af; text-align:center;">
This is an automated message — please don’t reply. Need help? Contact your HR administrator.
</div>
</x-mail::message>
