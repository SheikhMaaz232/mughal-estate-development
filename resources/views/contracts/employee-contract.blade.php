<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>ملازمت کا معاہدہ</title>
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.8;
            color: #222;
        }

        .contract-wrapper {
            width: 100%;
        }

        .header {
            border-bottom: 3px solid #1f2937;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        .contract-title {
            text-align: center;
            font-size: 18px;
            margin-top: 5px;
            font-weight: bold;
        }

        .contract-meta {
            margin-top: 10px;
            width: 100%;
        }

        .contract-meta td {
            padding: 4px;
        }

        .card {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .card-header {
            background: #f3f4f6;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #d1d5db;
        }

        .card-body {
            padding: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        .label {
            width: 25%;
            font-weight: bold;
            background: #f9fafb;
        }

        .clause {
            margin-bottom: 15px;
        }

        .clause-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            color: #111827;
        }

        .clause-content {
            text-align: justify;
        }

        ul {
            margin: 5px 0;
        }

        li {
            margin-bottom: 5px;
        }

        .declaration {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 12px;
        }

        .signature-table {
            width: 100%;
            margin-top: 50px;
        }

        .signature-table td {
            width: 33%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 180px;
            margin: 50px auto 10px;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="header" style="text-align: center;">
        <div class="title"> <h2>مغل اسٹیٹ ڈویلپرز</h2></div>
        <div class="subtitle"><h3>ملازمت کا معاہدہ</h3></div>
    </div>

    <div class="card">
        <div class="card-header">
            ملازم کی معلومات
        </div>

        <div class="card-body">
            <table class="info-table">
                <tr>
                    <td class="label">نام</td>
                    <td>{{ $employee->first_name_ur }} {{ $employee->last_name_ur }}</td>

                    <td class="label">شناختی کارڈ</td>
                    <td>{{ $employee->cnic }}</td>
                </tr>

                <tr>
                    <td class="label">والد کا نام</td>
                    <td>{{ $employee->father_name_ur }}</td>

                    <td class="label">شعبہ</td>
                    <td>{{ $employee->department?->title_ur }}</td>
                </tr>

                <tr>
                    <td class="label">عہدہ</td>
                    <td>{{ $employee->designation?->title_ur }}</td>

                    <td class="label">شفٹ</td>
                    <td>{{ $employee->shift?->shift_name_ur }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            ملازمت کی تفصیلات
        </div>

        <div class="card-body">
            <table class="info-table">
                <tr>
                    <td class="label">تقرری کی تاریخ</td>
                    <td>{{ $employee->joining_date }}</td>

                    <td class="label">بنیادی تنخواہ</td>
                    <td>Rs. {{ number_format($employee->basic_salary, 2) }}</td>
                </tr>

                <tr>
                    <td class="label">ملازمت کی نوعیت</td>
                    <td>مستقل</td>

                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">شرائط و ضوابط</div>

        <p>
            یہ ملازمت کا معاہدہ <b>مغل اسٹیٹ ڈویلپرز</b> اور
            <strong>{{ $employee->first_name_ur }} {{ $employee->last_name_ur }}</strong>
            کے درمیان طے پایا ہے۔ ملازم اس بات کا اقرار کرتا/کرتی ہے کہ وہ کمپنی کی
            پالیسیوں، قواعد و ضوابط اور پاکستان کے نافذ العمل قوانین کی مکمل پابندی کرے گا/گی۔
        </p>

        <ol>
            <li>
                <strong>تقرری اور ذمہ داریاں</strong>
                <ul>
                    <li>ملازم اپنی ذمہ داریاں ایمانداری، دیانت داری اور پیشہ ورانہ مہارت کے ساتھ انجام دے گا/گی۔</li>
                    <li>ملازم انتظامیہ اور اپنے سپروائزر کی جائز ہدایات پر عمل کرنے کا پابند ہوگا۔</li>
                    <li>کمپنی کی ضرورت کے مطابق ذمہ داریوں یا تعیناتی کے مقام میں مناسب تبدیلی کی جا سکتی ہے۔</li>
                </ul>
            </li>

            <li>
                <strong>اوقات کار</strong>
                <ul>
                    <li>ملازم مقررہ دفتری اوقات کی پابندی کرے گا/گی۔</li>
                    <li>تاخیر یا غیر حاضری کی صورت میں متعلقہ افسر کو فوری اطلاع دینا ضروری ہوگا۔</li>
                    <li>اضافی اوقات کار (Overtime) کمپنی کی پالیسی کے مطابق ہوں گے۔</li>
                </ul>
            </li>

            <li>
                <strong>تنخواہ اور مراعات</strong>
                <ul>
                    <li>ملازم کو ماہانہ بنیادی تنخواہ اور دیگر مراعات کمپنی کی پالیسی کے مطابق ادا کی جائیں گی۔</li>
                    <li>تمام حکومتی ٹیکس اور قانونی کٹوتیاں نافذ قوانین کے مطابق لاگو ہوں گی۔</li>
                    <li>ملازم کی ماہانہ حاضری کا ریکارڈ کمپنی کے حاضری کے کمپیوٹرائزڈ نظام/بائیومیٹرک ڈیوائس کے مطابق مرتب کیا جائے گا، اور اسی حاضری کی بنیاد پر کمپیوٹرائزڈ طور پر تیار ہونے والی تنخواہ ہی ملازم کی حتمی اور قابلِ قبول ماہانہ تنخواہ تصور ہوگی۔ حاضری کے ریکارڈ کے مطابق تنخواہ میں کسی بھی قسم کی کٹوتی یا اضافہ کمپنی کے مقررہ قواعد و ضوابط کے تحت کیا جائے گا۔
                    </li>
                </ul>
            </li>

            <li>
                <strong>چھٹیاں</strong>
                <ul>
                    <li>سالانہ چھٹی</li>
                    <li>بیماری کی چھٹی</li>
                    <li>ہنگامی چھٹی</li>
                    <li>سرکاری تعطیلات</li>
                    <li>تمام چھٹیوں کی منظوری متعلقہ افسر سے لینا ضروری ہوگی۔</li>
                </ul>
            </li>

            <li>
                <strong>رازداری (Confidentiality)</strong>
                <ul>
                    <li>کمپنی کی کاروباری معلومات، مالی ریکارڈ، کسٹمر ڈیٹا، سافٹ ویئر، پاس ورڈز اور دیگر خفیہ معلومات کو
                        محفوظ رکھا جائے گا۔</li>
                    <li>ملازم کسی غیر متعلقہ فرد یا ادارے کے ساتھ ایسی معلومات شیئر نہیں کرے گا/گی۔</li>
                    <li>ملازمت کے خاتمے کے بعد بھی رازداری کی ذمہ داری برقرار رہے گی۔</li>
                </ul>
            </li>

            <li>
                <strong>کمپنی کی ملکیت</strong>
                <ul>
                    <li>کمپنی کی طرف سے فراہم کردہ سامان مثلاً لیپ ٹاپ، موبائل فون، دستاویزات، سافٹ ویئر، چابیاں اور
                        دیگر اثاثے صرف دفتری مقاصد کے لیے استعمال ہوں گے۔</li>
                    <li>ملازمت ختم ہونے پر تمام سامان اچھی حالت میں واپس کرنا ضروری ہوگا۔</li>
                </ul>
            </li>

            <li>
                <strong>ضابطۂ اخلاق</strong>
                <ul>
                    <li>ملازم ادارے کے قوانین کی پابندی کرے گا/گی۔</li>
                    <li>تمام ساتھیوں اور گاہکوں سے احترام کے ساتھ پیش آئے گا/گی۔</li>
                    <li>ہراسانی، امتیازی سلوک، بدعنوانی یا غیر اخلاقی رویہ اختیار نہیں کیا جائے گا۔</li>
                    <li>کمپنی کی ساکھ کو نقصان پہنچانے والی کسی سرگرمی میں ملوث نہیں ہوگا/گی۔</li>
                </ul>
            </li>

            <li>
                <strong>کارکردگی کا جائزہ</strong>
                <ul>
                    <li>ملازم کی کارکردگی کا جائزہ وقتاً فوقتاً لیا جا سکتا ہے۔</li>
                    <li>غیر تسلی بخش کارکردگی پر انتظامیہ ضروری کارروائی کرنے کی مجاز ہوگی۔</li>
                </ul>
            </li>

            <li>
                <strong>ملازمت کا خاتمہ</strong>
                <ul>
                    <li>استعفیٰ دینے کی صورت میں ملازم کمپنی کو کم از کم تین (03) ماہ قبل تحریری نوٹس دینے کا پابند ہوگا۔</li>
                    <li>استعفیٰ دینے کی صورت میں ملازم کمپنی کی مقررہ نوٹس مدت پوری کرے گا/گی۔</li>
                    <li>قواعد و ضوابط کی خلاف ورزی، بدعنوانی، غیر حاضری، رازداری کی خلاف ورزی یا غیر تسلی بخش کارکردگی
                        کی بنیاد پر کمپنی ملازمت ختم کرنے کا حق محفوظ رکھتی ہے۔</li>
                    <li>ملازمت کے خاتمے پر کمپنی کی تمام ملکیت واپس کرنا لازم ہوگا۔</li>
                </ul>
            </li>

            <li>
                <strong>قانونی حیثیت</strong>
                <ul>
                    <li>یہ معاہدہ پاکستان کے نافذ العمل قوانین کے مطابق ہوگا۔</li>
                    <li>کسی بھی تنازع کی صورت میں متعلقہ عدالت یا مجاز اتھارٹی کا فیصلہ قابل قبول ہوگا۔</li>
                </ul>
            </li>
        </ol>
    </div>

    <div class="section">
        <div class="section-title"><b>ملازم کا اقرار</b></div>

        <p>میں تصدیق کرتا/کرتی ہوں کہ:</p>

        <ul>
            <li>میں نے اس معاہدے کی تمام شرائط و ضوابط بغور پڑھی ہیں۔</li>
            <li>میں نے ان شرائط کو مکمل طور پر سمجھ لیا ہے۔</li>
            <li>میں ان تمام شرائط پر اپنی آزاد مرضی سے عمل کرنے پر رضامند ہوں۔</li>
            <li>میں کمپنی کی موجودہ اور آئندہ جاری ہونے والی تمام پالیسیوں کی پابندی کروں گا/گی۔</li>
        </ul>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                ملازم
            </td>

            <td>
                <div class="signature-line"></div>
                HR مینیجر
            </td>

            <td>
                <div class="signature-line"></div>
                مجاز نمائندہ
            </td>
        </tr>

        <tr>
            <td style="padding-top:20px;">
                تاریخ: __________
            </td>

            <td style="padding-top:20px;">
                تاریخ: __________
            </td>

            <td style="padding-top:20px;">
                مہر کمپنی
            </td>
        </tr>
    </table>
</body>

</html>
