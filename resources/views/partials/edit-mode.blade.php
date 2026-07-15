{{-- Inline edit mode chrome — rendered only for logged-in admins (layout wraps in @auth). --}}
<button id="gs-edit-fab" title="Edit website" style="position: fixed; bottom: 24px; inset-inline-end: 24px; z-index: 90; width: 58px; height: 58px; border-radius: 50%; background: #0d1826; color: #fff; border: none; cursor: pointer; box-shadow: 0 18px 44px rgba(13,24,38,.4); display: grid; place-items: center; transition: .2s;">
    <svg data-fab-pencil width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    <svg data-fab-check style="display: none;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
</button>
<div id="gs-edit-toolbar" style="display: none; position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 90; align-items: center; gap: 14px; background: #0d1826; color: #d0d9e1; padding: 12px 16px; border-radius: 14px; box-shadow: 0 18px 44px rgba(13,24,38,.45); font-size: 13px; max-width: min(92vw, 760px); border: 1px solid rgba(255,255,255,.12);">
    <span style="font-family: 'Space Grotesk'; font-weight: 700; color: #6FDED3; letter-spacing: .08em; font-size: 12px; flex-shrink: 0;">EDIT MODE</span>
    <span style="line-height: 1.5;">Click any text to edit it in place. Click an image to swap it (upload or URL). Drag cards to reorder, ✕ deletes, ＋ tiles add. Section chips move or hide sections. Everything saves to the CMS.</span>
    <a href="{{ route('admin.dashboard') }}" style="border: 1px solid rgba(255,255,255,.22); background: transparent; color: #fff; padding: 8px 13px; border-radius: 9px; cursor: pointer; font-family: 'Space Grotesk'; font-weight: 600; font-size: 12.5px; flex-shrink: 0; text-decoration: none;">Admin panel</a>
</div>
<div id="gs-edit-toast" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 95; background: #0d1826; color: #fff; padding: 10px 18px; border-radius: 10px; font-family: 'Space Grotesk'; font-size: 13.5px; box-shadow: 0 12px 30px rgba(13,24,38,.4); border: 1px solid rgba(255,255,255,.15);"></div>
<div id="gs-media-modal" style="display: none;"></div>
<script>
    window.GS_EDIT_ROUTES = {
        inlineText: @json(route('admin.api.inline-text')),
        inlineImage: @json(route('admin.api.inline-image')),
        sections: @json(route('admin.api.sections')),
        reorder: @json(route('admin.api.reorder')),
        items: @json(route('admin.api.items.store')),
        media: @json(route('admin.media.index')),
        mediaUpload: @json(route('admin.media.store')),
    };
</script>
