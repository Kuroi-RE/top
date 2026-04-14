@extends('layouts.app')

@section('title', 'Input Template Dokumen')

@section('content')
<style>
    .template-wrap {
        position: relative;
        width: 100%;
        max-width: 980px;
        margin: 0 auto;
    }

    .template-close-btn {
        position: absolute;
        top: 8px;
        left: -40px;
        width: 22px;
        height: 22px;
        border: none;
        border-radius: 6px;
        background: #d80f1f;
        color: #fff;
        font-size: 14px;
        line-height: 22px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(216, 15, 31, 0.18);
        z-index: 20;
    }

    .template-card {
        width: 100%;
        max-width: 860px;
        margin: 8px auto 0;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
        padding: 28px 32px 20px;
        box-sizing: border-box;
    }

    .template-title {
        margin: 0 0 24px 0;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.2;
        color: #1a1a1a;
    }

    .template-form-row {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 18px;
        align-items: start;
        margin-bottom: 16px;
    }

    .template-label {
        font-size: 13px;
        color: #555;
        padding-top: 8px;
        line-height: 1.4;
    }

    .template-input {
        width: 100%;
        height: 34px;
        border: 1px solid #8f8f8f;
        border-radius: 9999px;
        background: #fff;
        padding: 0 16px;
        font-size: 13px;
        color: #222;
        outline: none;
        box-sizing: border-box;
    }

    .template-input:focus {
        border-color: #c41422;
        box-shadow: 0 0 0 3px rgba(196, 20, 34, 0.08);
    }

    .template-upload-box {
        display: block;
        width: 100%;
        border: 1px solid #9a9a9a;
        border-radius: 12px;
        background: #fafafa;
        padding: 6px;
        cursor: pointer;
        box-sizing: border-box;
    }

    .template-upload-input {
        display: none;
    }

    .template-upload-inner {
        min-height: 84px;
        border: 1px dashed #b8b8b8;
        border-radius: 9px;
        background: #fcfcfc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        text-align: center;
        padding: 10px;
        box-sizing: border-box;
    }

    .template-upload-icon {
        width: 16px;
        height: 16px;
        color: #444;
    }

    .template-upload-text {
        font-size: 11px;
        line-height: 1.2;
        color: #666;
    }

    .template-submit-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px;
    }

    .template-submit-btn {
        height: 40px;
        border: none;
        border-radius: 9999px;
        background: #c30f1d;
        color: #fff;
        padding: 0 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .template-submit-btn:hover {
        background: #aa0d19;
    }

    .template-submit-icon {
        width: 15px;
        height: 15px;
    }

    @media (max-width: 768px) {
        .template-close-btn {
            left: 0;
            top: -14px;
        }

        .template-card {
            padding: 22px 18px 18px;
        }

        .template-form-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .template-label {
            padding-top: 0;
        }
    }
</style>

<div class="template-wrap">

    <div class="template-card">
        <h1 class="template-title">Input Template Dokumen</h1>

        <form method="POST" action="#" enctype="multipart/form-data">
            @csrf

            <div class="template-form-row">
                <label for="nama_dokumen" class="template-label">Nama Dokumen</label>
                <input
                    type="text"
                    id="nama_dokumen"
                    name="nama_dokumen"
                    class="template-input"
                >
            </div>

            <div class="template-form-row" style="margin-bottom: 0;">
                <label for="dokumen" class="template-label" style="padding-top: 12px;">Dokumen</label>

                <label for="dokumen" class="template-upload-box">
                    <input
                        type="file"
                        id="dokumen"
                        name="dokumen"
                        class="template-upload-input"
                    >

                    <div class="template-upload-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="template-upload-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>
                        <span class="template-upload-text">Upload dokumen disini</span>
                    </div>
                </label>
            </div>

            <div class="template-submit-row">
                <button type="submit" class="template-submit-btn">
                    Kirim
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h13m0 0l-4-4m4 4l-4 4" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
