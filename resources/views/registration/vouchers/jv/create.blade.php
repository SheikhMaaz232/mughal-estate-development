@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">

        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('messages.jv')
            </h3>
        </div>

        <div class="block-content block-content-full">

            <form id="jvForm" action="{{ !empty($jv) ? route('jv-voucher.update') : route('jv-voucher.store') }}"
                method="POST" class="row g-3">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $maxid }}" />


                {{-- =========================================================
                 Voucher Information
                 ========================================================== --}}

                <div class="col-md-12">

                    <div class="row">

                        <div class="col-lg-6 mt-4">

                            <label>
                                <h4>
                                    @lang('messages.voucher_no')
                                    {{ @$maxid }}
                                    {{ @$currentid }}
                                </h4>
                            </label>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                @lang('messages.Date')
                            </label>

                            <input type="date" name="voucher_date" value="{{ $jv->voucher_date ?? date('Y-m-d') }}"
                                class="form-control form-control-sm" required>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                 Description + Urdu Keyboard + Microphone
            ========================================================== --}}

                <div class="col-md-12">

                    <label class="form-label">
                        @lang('messages.description')
                    </label>


                    <textarea name="description" id="description" class="form-control form-control-sm" rows="5" autocomplete="off"
                        dir="rtl">{{ old('description', $jv->description ?? '') }}</textarea>


                    {{-- =====================================================
                     Keyboard Toolbar
                ====================================================== --}}

                    <div id="urduKeyboardToolbar" class="urdu-keyboard-toolbar">

                        {{-- Urdu --}}

                        <button type="button" class="keyboard-lang active" data-lang="urdu">
                            اردو
                        </button>


                        {{-- English --}}

                        <button type="button" class="keyboard-lang" data-lang="english">
                            English
                        </button>


                        {{-- Microphone --}}

                        <button type="button" id="startSpeech" class="keyboard-mic" title="Voice typing">
                            🎤
                        </button>


                        {{-- Close --}}

                        <button type="button" id="closeUrduKeyboard" class="keyboard-close" title="Close keyboard">
                            ✕
                        </button>

                    </div>


                    {{-- =====================================================
                     Virtual Keyboard
                ====================================================== --}}

                    <div id="urduKeyboard" class="urdu-keyboard"></div>

                </div>


                {{-- =========================================================
                 JV Detail Table
            ========================================================== --}}

                <div class="tab-content" id="pills-tabContent">

                    <div class="invoice-detail-items" style="padding: 0px;">

                        <div class="table-responsive">

                            <table class="table item-table">

                                <thead>

                                    <tr>

                                        <th></th>

                                        <th style="width:30% !important;">
                                            @lang('messages.debit_account')
                                        </th>

                                        <th style="width:30% !important;">
                                            @lang('messages.credit_account')
                                        </th>

                                        <th style="width:20% !important;">
                                            @lang('messages.debit')
                                        </th>

                                        <th style="width:20% !important;">
                                            @lang('messages.credit')
                                        </th>

                                    </tr>


                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>

                                </thead>


                                <tbody>
                                </tbody>

                            </table>

                        </div>


                        <a href="javascript:void(0);" class="btn btn-dark additem mt-3" id="add-item">
                            @lang('messages.add_details')
                        </a>

                    </div>


                    {{-- =====================================================
                     Totals
                ====================================================== --}}

                    <div class="col-xl-6 invoice-address-client invoice-detail-total mt-3" style="float:right">

                        <div class="invoice-address-client-fields">


                            {{-- Total Debit --}}

                            <div class="form-group row">

                                <label for="total_debit" class="col-sm-4 col-form-label col-form-label-sm">
                                    @lang('messages.total_debit')
                                </label>

                                <div class="col-sm-8">

                                    <input type="text" id="total_debit" class="form-control form-control-sm gross-amount"
                                        name="total_debit" style="color:black" placeholder="@lang('messages.total_debit')" readonly>

                                </div>

                            </div>


                            {{-- Total Credit --}}

                            <div class="form-group row">

                                <label for="total_credit" class="col-sm-4 col-form-label col-form-label-sm">
                                    @lang('messages.total_credit')
                                </label>

                                <div class="col-sm-8">

                                    <input type="text" id="total_credit"
                                        class="form-control form-control-sm total_credit" name="total_credit"
                                        style="color:black" placeholder="@lang('messages.total_credit')" readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                 Buttons
            ========================================================== --}}

                <div class="col-xl-12">

                    <a href="{{ route('jv-voucher.index') }}" style="float:right;"
                        class="btn btn-dark rounded bs-popover ml-2 mt-5 mb-4">
                        @lang('messages.cancel')
                    </a>


                    <button type="submit" style="float:right"
                        class="btn btn-success rounded bs-popover me-1 mt-5 mb-4 mr-5">
                        {{ isset($jv) ? __('messages.update') : __('messages.save') }}
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================================
     CSS
    ====================================================================== --}}

    <style>
        /*
        |--------------------------------------------------------------------------
        | Keyboard Toolbar
        |--------------------------------------------------------------------------
        */

        .urdu-keyboard-toolbar {

            display: none;

            align-items: center;

            gap: 6px;

            margin-top: 8px;

        }


        .urdu-keyboard-toolbar.show {

            display: flex;

        }


        /*
        |--------------------------------------------------------------------------
        | Language Buttons
        |--------------------------------------------------------------------------
        */

        .keyboard-lang,
        .keyboard-close,
        .keyboard-mic {

            border: 1px solid #ced4da;

            background: #fff;

            border-radius: 5px;

            padding: 6px 14px;

            cursor: pointer;

            font-size: 14px;

            transition: all .15s ease;

        }


        .keyboard-lang:hover,
        .keyboard-close:hover,
        .keyboard-mic:hover {

            background: #e9ecef;

        }


        .keyboard-lang.active {

            background: #212529;

            color: #fff;

            border-color: #212529;

        }


        /*
        |--------------------------------------------------------------------------
        | Microphone
        |--------------------------------------------------------------------------
        */

        .keyboard-mic {

            width: 44px;

            height: 36px;

            padding: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 19px;

        }


        .keyboard-mic.recording {

            background: #dc3545;

            color: #fff;

            border-color: #dc3545;

            animation: microphonePulse 1s infinite;

        }


        @keyframes microphonePulse {

            0% {

                transform: scale(1);

            }

            50% {

                transform: scale(1.08);

            }

            100% {

                transform: scale(1);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Close
        |--------------------------------------------------------------------------
        */

        .keyboard-close {

            margin-left: auto;

            color: #dc3545;

        }


        /*
        |--------------------------------------------------------------------------
        | Virtual Keyboard
        |--------------------------------------------------------------------------
        */

        .urdu-keyboard {

            display: none;

            margin-top: 8px;

            padding: 10px;

            border: 1px solid #dee2e6;

            border-radius: 7px;

            background: #f8f9fa;

            direction: rtl;

            position: relative;

            z-index: 9999;

        }


        .urdu-keyboard.show {

            display: block;

        }


        /*
        |--------------------------------------------------------------------------
        | Keyboard Rows
        |--------------------------------------------------------------------------
        */

        .keyboard-row {

            display: flex;

            justify-content: center;

            gap: 5px;

            margin-bottom: 5px;

        }


        /*
        |--------------------------------------------------------------------------
        | Keyboard Keys
        |--------------------------------------------------------------------------
        */

        .keyboard-key {

            min-width: 42px;

            min-height: 44px;

            padding: 5px 8px;

            border: 1px solid #ced4da;

            border-radius: 5px;

            background: #fff;

            cursor: pointer;

            font-size: 20px;

            font-family:
                "Noto Nastaliq Urdu",
                "Jameel Noori Nastaleeq",
                "Noto Sans Arabic",
                Arial,
                sans-serif;

            user-select: none;

            transition: all .1s ease;

        }


        .keyboard-key:hover {

            background: #e9ecef;

        }


        .keyboard-key:active {

            transform: scale(.95);

            background: #dee2e6;

        }


        /*
        |--------------------------------------------------------------------------
        | Special Buttons
        |--------------------------------------------------------------------------
        */

        .keyboard-key.special {

            min-width: 80px;

            font-size: 14px;

            font-family: Arial, sans-serif;

        }


        .keyboard-key.space {

            min-width: 250px;

            font-size: 14px;

            font-family: Arial, sans-serif;

        }


        /*
        |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        */

        #description {

            min-height: 130px;

            resize: vertical;

            direction: rtl;

            text-align: right;

        }


        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 768px) {

            .keyboard-row {

                flex-wrap: wrap;

            }


            .keyboard-key {

                min-width: 35px;

                min-height: 40px;

                font-size: 18px;

            }


            .keyboard-key.space {

                min-width: 180px;

            }


            .keyboard-lang,
            .keyboard-close,
            .keyboard-mic {

                padding: 6px 10px;

            }

        }
    </style>


    {{-- =====================================================================
     JavaScript
    ====================================================================== --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {


            /*
            |--------------------------------------------------------------------------
            | Elements
            |--------------------------------------------------------------------------
            */

            const textarea =
                document.getElementById("description");


            const keyboard =
                document.getElementById("urduKeyboard");


            const toolbar =
                document.getElementById("urduKeyboardToolbar");


            const closeButton =
                document.getElementById("closeUrduKeyboard");


            const micButton =
                document.getElementById("startSpeech");


            const languageButtons =
                document.querySelectorAll(".keyboard-lang");


            /*
            |--------------------------------------------------------------------------
            | Safety check
            |--------------------------------------------------------------------------
            */

            if (!textarea || !keyboard || !toolbar) {

                console.error(
                    "Keyboard: required elements not found."
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Current language
            |--------------------------------------------------------------------------
            */

            let currentLanguage = "urdu";


            /*
            |--------------------------------------------------------------------------
            | Urdu keyboard
            |--------------------------------------------------------------------------
            */

            const urduRows = [

                [
                    "ء",
                    "آ",
                    "ا",
                    "ب",
                    "پ",
                    "ت",
                    "ٹ",
                    "ث",
                    "ج",
                    "چ"
                ],

                [
                    "ح",
                    "خ",
                    "د",
                    "ڈ",
                    "ذ",
                    "ر",
                    "ڑ",
                    "ز",
                    "ژ",
                    "س"
                ],

                [
                    "ش",
                    "ص",
                    "ض",
                    "ط",
                    "ظ",
                    "ع",
                    "غ",
                    "ف",
                    "ق",
                    "ک"
                ],

                [
                    "گ",
                    "ل",
                    "م",
                    "ن",
                    "ں",
                    "و",
                    "ہ",
                    "ھ",
                    "ی",
                    "ے"
                ],

                [
                    "ئ",
                    "ؤ",
                    "ۓ",
                    "،",
                    "۔",
                    "؟",
                    "!",
                    "(",
                    ")"
                ]

            ];


            /*
            |--------------------------------------------------------------------------
            | English keyboard
            |--------------------------------------------------------------------------
            */

            const englishRows = [

                [
                    "q",
                    "w",
                    "e",
                    "r",
                    "t",
                    "y",
                    "u",
                    "i",
                    "o",
                    "p"
                ],

                [
                    "a",
                    "s",
                    "d",
                    "f",
                    "g",
                    "h",
                    "j",
                    "k",
                    "l"
                ],

                [
                    "z",
                    "x",
                    "c",
                    "v",
                    "b",
                    "n",
                    "m"
                ],

                [
                    "1",
                    "2",
                    "3",
                    "4",
                    "5",
                    "6",
                    "7",
                    "8",
                    "9",
                    "0"
                ],

                [
                    ",",
                    ".",
                    "?",
                    "!",
                    "-",
                    "_",
                    "@",
                    "#"
                ]

            ];


            /*
            |--------------------------------------------------------------------------
            | Create keyboard
            |--------------------------------------------------------------------------
            */

            function createKeyboard() {
                keyboard.innerHTML = "";

                const rows =
                    currentLanguage === "urdu" ?
                    urduRows :
                    englishRows;


                rows.forEach(function(row) {


                    const rowElement =
                        document.createElement("div");


                    rowElement.className =
                        "keyboard-row";


                    row.forEach(function(key) {


                        const button =
                            document.createElement("button");


                        button.type = "button";


                        button.className =
                            "keyboard-key";


                        button.textContent =
                            key;


                        /*
                        |--------------------------------------------------------------------------
                        | Don't lose textarea cursor
                        |--------------------------------------------------------------------------
                        */

                        button.addEventListener(
                            "mousedown",
                            function(event) {

                                event.preventDefault();

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Insert character
                        |--------------------------------------------------------------------------
                        */

                        button.addEventListener(
                            "click",
                            function() {

                                insertText(key);

                            }
                        );


                        rowElement.appendChild(button);

                    });


                    keyboard.appendChild(rowElement);

                });


                /*
                |--------------------------------------------------------------------------
                | Special row
                |--------------------------------------------------------------------------
                */

                const specialRow =
                    document.createElement("div");


                specialRow.className =
                    "keyboard-row";


                /*
                |--------------------------------------------------------------------------
                | Backspace
                |--------------------------------------------------------------------------
                */

                const backspace =
                    document.createElement("button");


                backspace.type = "button";


                backspace.className =
                    "keyboard-key special";


                backspace.textContent = "⌫";


                backspace.addEventListener(
                    "mousedown",
                    function(event) {

                        event.preventDefault();

                    }
                );


                backspace.addEventListener(
                    "click",
                    function() {

                        backspaceText();

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Space
                |--------------------------------------------------------------------------
                */

                const space =
                    document.createElement("button");


                space.type = "button";


                space.className =
                    "keyboard-key space";


                space.textContent = "SPACE";


                space.addEventListener(
                    "mousedown",
                    function(event) {

                        event.preventDefault();

                    }
                );


                space.addEventListener(
                    "click",
                    function() {

                        insertText(" ");

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Enter
                |--------------------------------------------------------------------------
                */

                const enter =
                    document.createElement("button");


                enter.type = "button";


                enter.className =
                    "keyboard-key special";


                enter.textContent = "Enter";


                enter.addEventListener(
                    "mousedown",
                    function(event) {

                        event.preventDefault();

                    }
                );


                enter.addEventListener(
                    "click",
                    function() {

                        insertText("\n");

                    }
                );


                specialRow.appendChild(backspace);

                specialRow.appendChild(space);

                specialRow.appendChild(enter);


                keyboard.appendChild(specialRow);

            }


            /*
            |--------------------------------------------------------------------------
            | Insert text
            |--------------------------------------------------------------------------
            */

            function insertText(text) {


                const start =
                    textarea.selectionStart;


                const end =
                    textarea.selectionEnd;


                const value =
                    textarea.value;


                textarea.value =
                    value.substring(0, start) +
                    text +
                    value.substring(end);


                const newPosition =
                    start + text.length;


                textarea.selectionStart =
                    newPosition;


                textarea.selectionEnd =
                    newPosition;


                textarea.dispatchEvent(
                    new Event("input", {
                        bubbles: true
                    })
                );


                textarea.focus();

            }


            /*
            |--------------------------------------------------------------------------
            | Backspace
            |--------------------------------------------------------------------------
            */

            function backspaceText() {


                const start =
                    textarea.selectionStart;


                const end =
                    textarea.selectionEnd;


                const value =
                    textarea.value;


                /*
                |--------------------------------------------------------------------------
                | Selected text
                |--------------------------------------------------------------------------
                */

                if (start !== end) {


                    textarea.value =
                        value.substring(0, start) +
                        value.substring(end);


                    textarea.selectionStart =
                        start;


                    textarea.selectionEnd =
                        start;

                }


                /*
                |--------------------------------------------------------------------------
                | Normal backspace
                |--------------------------------------------------------------------------
                */
                else if (start > 0) {


                    textarea.value =
                        value.substring(0, start - 1) +
                        value.substring(end);


                    textarea.selectionStart =
                        start - 1;


                    textarea.selectionEnd =
                        start - 1;

                }


                textarea.dispatchEvent(
                    new Event("input", {
                        bubbles: true
                    })
                );


                textarea.focus();

            }


            /*
            |--------------------------------------------------------------------------
            | Show keyboard
            |--------------------------------------------------------------------------
            */

            function showKeyboard() {


                keyboard.classList.add("show");

                toolbar.classList.add("show");

            }


            /*
            |--------------------------------------------------------------------------
            | Hide keyboard
            |--------------------------------------------------------------------------
            */

            function hideKeyboard() {


                keyboard.classList.remove("show");

                toolbar.classList.remove("show");

            }


            /*
            |--------------------------------------------------------------------------
            | Textarea focus
            |--------------------------------------------------------------------------
            */

            textarea.addEventListener(
                "focus",
                function() {

                    showKeyboard();

                }
            );


            textarea.addEventListener(
                "click",
                function() {

                    showKeyboard();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Language switching
            |--------------------------------------------------------------------------
            */

            languageButtons.forEach(
                function(button) {


                    button.addEventListener(
                        "click",
                        function(event) {


                            event.preventDefault();

                            event.stopPropagation();


                            currentLanguage =
                                button.getAttribute("data-lang");


                            /*
                            |--------------------------------------------------------------------------
                            | Active button
                            |--------------------------------------------------------------------------
                            */

                            languageButtons.forEach(
                                function(btn) {

                                    btn.classList.remove(
                                        "active"
                                    );

                                }
                            );


                            button.classList.add("active");


                            /*
                            |--------------------------------------------------------------------------
                            | Direction
                            |--------------------------------------------------------------------------
                            */

                            if (
                                currentLanguage === "urdu"
                            ) {


                                textarea.style.direction =
                                    "rtl";


                                textarea.style.textAlign =
                                    "right";


                            } else {


                                textarea.style.direction =
                                    "ltr";


                                textarea.style.textAlign =
                                    "left";

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Recreate keyboard
                            |--------------------------------------------------------------------------
                            */

                            createKeyboard();


                            textarea.focus();

                        }
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Close keyboard
            |--------------------------------------------------------------------------
            */

            closeButton.addEventListener(
                "click",
                function(event) {


                    event.preventDefault();

                    event.stopPropagation();


                    hideKeyboard();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Prevent keyboard from taking focus
            |--------------------------------------------------------------------------
            */

            keyboard.addEventListener(
                "mousedown",
                function(event) {

                    event.preventDefault();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Voice Typing
            |--------------------------------------------------------------------------
            */

            const SpeechRecognition =
                window.SpeechRecognition ||
                window.webkitSpeechRecognition;


            if (!SpeechRecognition) {


                /*
                |--------------------------------------------------------------------------
                | Browser doesn't support speech recognition
                |--------------------------------------------------------------------------
                */

                micButton.addEventListener(
                    "click",
                    function() {


                        Swal.fire({

                            icon: "warning",

                            title: "Voice typing not supported",

                            text: "Please use Google Chrome or Microsoft Edge for voice typing.",

                            confirmButtonText: "OK"

                        });

                    }
                );


            } else {


                /*
                |--------------------------------------------------------------------------
                | Create recognition
                |--------------------------------------------------------------------------
                */

                const recognition =
                    new SpeechRecognition();


                /*
                |--------------------------------------------------------------------------
                | Configuration
                |--------------------------------------------------------------------------
                */

                recognition.continuous = true;

                recognition.interimResults = false;


                let isRecording = false;


                /*
                |--------------------------------------------------------------------------
                | Start microphone
                |--------------------------------------------------------------------------
                */

                micButton.addEventListener(
                    "click",
                    function() {


                        if (!isRecording) {


                            /*
                            |--------------------------------------------------------------------------
                            | Set language
                            |--------------------------------------------------------------------------
                            */

                            recognition.lang =
                                currentLanguage === "urdu" ?
                                "ur-PK" :
                                "en-US";


                            /*
                            |--------------------------------------------------------------------------
                            | Focus textarea
                            |--------------------------------------------------------------------------
                            */

                            textarea.focus();


                            try {

                                recognition.start();

                            } catch (error) {

                                console.log(
                                    "Recognition start error:",
                                    error
                                );

                            }


                        } else {


                            recognition.stop();

                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Recognition started
                |--------------------------------------------------------------------------
                */

                recognition.onstart =
                    function() {


                        isRecording = true;


                        micButton.classList.add(
                            "recording"
                        );


                        micButton.innerHTML =
                            "⏹️";


                        micButton.title =
                            "Stop voice typing";


                    };


                /*
                |--------------------------------------------------------------------------
                | Speech result
                |--------------------------------------------------------------------------
                */

                recognition.onresult =
                    function(event) {


                        let finalText = "";


                        for (
                            let i = event.resultIndex; i < event.results.length; i++
                        ) {


                            if (
                                event.results[i].isFinal
                            ) {


                                finalText +=
                                    event.results[i][0]
                                    .transcript;

                            }

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Insert recognized text
                        |--------------------------------------------------------------------------
                        */

                        if (
                            finalText.trim() !== ""
                        ) {


                            insertText(
                                finalText.trim() + " "
                            );

                        }

                    };


                /*
                |--------------------------------------------------------------------------
                | Error
                |--------------------------------------------------------------------------
                */

                recognition.onerror =
                    function(event) {


                        console.error(
                            "Speech recognition error:",
                            event.error
                        );


                        if (
                            event.error === "not-allowed"
                        ) {


                            Swal.fire({

                                icon: "error",

                                title: "Microphone permission denied",

                                text: "Please allow microphone access in your browser and try again.",

                                confirmButtonText: "OK"

                            });

                        }


                        if (
                            event.error === "audio-capture"
                        ) {


                            Swal.fire({

                                icon: "error",

                                title: "Microphone unavailable",

                                text: "Please check that your microphone is connected and working.",

                                confirmButtonText: "OK"

                            });

                        }

                    };


                /*
                |--------------------------------------------------------------------------
                | Recognition ended
                |--------------------------------------------------------------------------
                */

                recognition.onend =
                    function() {


                        isRecording = false;


                        micButton.classList.remove(
                            "recording"
                        );


                        micButton.innerHTML =
                            "🎤";


                        micButton.title =
                            "Voice typing";

                    };

            }


            /*
            |--------------------------------------------------------------------------
            | Initialize keyboard
            |--------------------------------------------------------------------------
            */

            createKeyboard();

        });


        /* ========================================================================
           JV FORM VALIDATION
        ======================================================================== */

        document
            .getElementById("jvForm")
            .addEventListener(
                "submit",
                function(e) {


                    const debitAmount =
                        parseFloat(
                            document
                            .getElementById("total_debit")
                            .value
                        ) || 0;


                    const creditAmount =
                        parseFloat(
                            document
                            .getElementById("total_credit")
                            .value
                        ) || 0;


                    /*
                    |--------------------------------------------------------------------------
                    | Debit and credit must be equal
                    |--------------------------------------------------------------------------
                    */

                    if (
                        Math.abs(
                            debitAmount - creditAmount
                        ) > 0.001
                    ) {


                        e.preventDefault();


                        Swal.fire({

                            icon: "error",

                            title: "@lang('messages.error')",

                            text: "@lang('messages.debit_credit_equal')",

                            confirmButtonText: "@lang('messages.ok')"

                        });


                        return false;

                    }

                }
            );


        /* ========================================================================
           SELECT2
        ======================================================================== */

        function initializeDetailAccountSelect2(element) {


            $(element).select2({

                theme: "bootstrap-5",

                placeholder: "{{ __('messages.select-an-option') }}",

                allowClear: true,

                ajax: {

                    url: "{{ route('clients.select2') }}",

                    dataType: "json",

                    delay: 250,


                    data: function(params) {

                        return {

                            search: params.term || "",

                            page: params.page || 1

                        };

                    },


                    processResults: function(data, params) {


                        params.page =
                            params.page || 1;


                        return {

                            results: data.results,

                            pagination: {

                                more: data.pagination.more

                            }

                        };

                    },


                    cache: true

                },

                minimumInputLength: 1

            });

        }


        /* ========================================================================
           ADD DETAIL ITEM
        ======================================================================== */

        document
            .getElementsByClassName("additem")[0]
            .addEventListener(
                "click",
                function() {


                    const table =
                        document.querySelector(
                            ".item-table"
                        );


                    const currentIndex =
                        table.tBodies[0].rows.length;


                    const html = `

                <tr>

                    <td class="delete-item-row">

                        <ul class="table-controls">

                            <li>

                                <a
                                    href="javascript:void(0);"
                                    class="delete-item"
                                    title="Delete"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-x-circle"
                                    >

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="10"
                                        ></circle>

                                        <line
                                            x1="15"
                                            y1="9"
                                            x2="9"
                                            y2="15"
                                        ></line>

                                        <line
                                            x1="9"
                                            y1="9"
                                            x2="15"
                                            y2="15"
                                        ></line>

                                    </svg>

                                </a>

                            </li>

                        </ul>

                    </td>


                    <td hidden>

                        <input
                            type="hidden"
                            name="row_id[]"
                            class="row_id"
                            value="${currentIndex}"
                        >

                    </td>


                    <td class="description">

                        <select
                            name="debit_detail_account_id[]"
                            class="form-control form-select debit-account select2"
                        >

                            <option value=""></option>

                        </select>

                    </td>


                    <td class="description">

                        <select
                            name="credit_detail_account_id[]"
                            class="form-control form-select credit-account select2"
                        >

                            <option value=""></option>

                        </select>

                    </td>


                    <td class="text-right qty">

                        <input
                            type="number"
                            name="debit[]"
                            placeholder="@lang('messages.debit')"
                            class="form-control form-control-sm debit"
                            step="any"
                        >

                    </td>


                    <td class="text-right qty">

                        <input
                            type="number"
                            name="credit[]"
                            placeholder="@lang('messages.credit')"
                            class="form-control form-control-sm credit"
                            step="any"
                        >

                    </td>

                </tr>


                <tr>

                    <td></td>


                    <td class="text-right qty">

                        <textarea
                            name="detail_description_en[]"
                            placeholder="@lang('messages.description_en')"
                            class="form-control form-control-sm detail_description_en"
                            rows="2"
                        ></textarea>

                    </td>


                    <td class="text-right qty">

                        <textarea
                            name="detail_description_ur[]"
                            placeholder="@lang('messages.description_ur')"
                            class="form-control form-control-sm detail_description_ur"
                            rows="2"
                            dir="rtl"
                        ></textarea>

                    </td>


                    <td></td>

                    <td></td>

                </tr>

            `;


                    $(".item-table tbody")
                        .append(html);


                    /*
                    |--------------------------------------------------------------------------
                    | Select2
                    |--------------------------------------------------------------------------
                    */

                    initializeDetailAccountSelect2(
                        $(".item-table tbody .debit-account").last()
                    );


                    initializeDetailAccountSelect2(
                        $(".item-table tbody .credit-account").last()
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Delete handler
                    |--------------------------------------------------------------------------
                    */

                    deleteItemRow();


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate totals
                    |--------------------------------------------------------------------------
                    */

                    calculateTotals();

                }
            );


        /* ========================================================================
           DELETE ITEM
        ======================================================================== */

        function deleteItemRow() {


            document
                .querySelectorAll(".delete-item")
                .forEach(
                    function(deleteButton) {


                        /*
                        |--------------------------------------------------------------------------
                        | Avoid duplicate event listeners
                        |--------------------------------------------------------------------------
                        */

                        if (
                            deleteButton.dataset.listenerAttached === "true"
                        ) {

                            return;

                        }


                        deleteButton.dataset.listenerAttached =
                            "true";


                        deleteButton.addEventListener(
                            "click",
                            function() {


                                const firstRow =
                                    this.closest("tr");


                                if (!firstRow) {

                                    return;

                                }


                                const secondRow =
                                    firstRow.nextElementSibling;


                                /*
                                |--------------------------------------------------------------------------
                                | Remove description row
                                |--------------------------------------------------------------------------
                                */

                                if (secondRow) {

                                    secondRow.remove();

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Remove account row
                                |--------------------------------------------------------------------------
                                */

                                firstRow.remove();


                                /*
                                |--------------------------------------------------------------------------
                                | Recalculate
                                |--------------------------------------------------------------------------
                                */

                                calculateTotals();

                            }
                        );

                    }
                );

        }


        /* ========================================================================
           CALCULATE TOTALS
        ======================================================================== */

        function calculateTotals() {


            let totalDebit = 0;

            let totalCredit = 0;


            /*
            |--------------------------------------------------------------------------
            | Debit
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll(".debit")
                .forEach(
                    function(input) {


                        const value =
                            parseFloat(input.value);


                        if (!isNaN(value)) {

                            totalDebit += value;

                        }

                    }
                );


            /*
            |--------------------------------------------------------------------------
            | Credit
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll(".credit")
                .forEach(
                    function(input) {


                        const value =
                            parseFloat(input.value);


                        if (!isNaN(value)) {

                            totalCredit += value;

                        }

                    }
                );


            /*
            |--------------------------------------------------------------------------
            | Update fields
            |--------------------------------------------------------------------------
            */

            document
                .getElementById("total_debit")
                .value =
                totalDebit.toFixed(2);


            document
                .getElementById("total_credit")
                .value =
                totalCredit.toFixed(2);

        }


        /* ========================================================================
           DEBIT / CREDIT EVENTS
        ======================================================================== */

        $(document).on(
            "input",
            ".debit, .credit",
            function() {

                calculateTotals();

            }
        );


        /* ========================================================================
           INITIALIZE
        ======================================================================== */

        deleteItemRow();


        $(document).ready(
            function() {

                $(".select2").select2();

            }
        );
    </script>


    {{-- =====================================================================
     SweetAlert
    ====================================================================== --}}

    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>


    {{-- =====================================================================
     Existing JV JavaScript
    ====================================================================== --}}

    <script src="{{ asset('js/jvVoucher.js') }}"></script>
@endsection
