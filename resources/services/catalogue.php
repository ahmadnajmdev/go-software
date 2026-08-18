<?php

/**
 * The service catalogue: one page's worth of copy per service, in all three
 * languages.
 *
 * Held as data rather than in ui_strings because a service page is a single
 * argument that has to hold together — headline, who it is for, what you get,
 * process, objections — and reads badly when edited a line at a time next to
 * button labels. The service ROW in the database still owns the card image,
 * tag and ordering, so the admin panel keeps working.
 *
 * Copy is outcome-led on purpose: the previous cards described technology
 * ("Web App Development"), which is what we sell, not what anyone buys.
 *
 * Pricing blocks are deliberately absent — see BLOCKED.md.
 */
return [

    'website-development' => [
        'tag' => 'WEB',
        'whatsapp' => 'service_website',
        'icon' => 'globe',
        'en' => [
            'name' => 'Website Development',
            'title' => 'Website Design & Development in Erbil | GoSoftware',
            'meta' => 'We build websites that bring Iraqi businesses customers — fast, in Kurdish, Arabic and English, and easy for your team to update. Free estimate within one working day.',
            'h1' => 'A website that brings you customers — not just an online business card',
            'card' => 'A site that works for you: fast, findable on Google, and written in the languages your customers actually use.',
            'intro' => 'Most business websites in Iraq are a logo, a phone number and a page of text nobody reads. That is a business card, and a business card does not sell anything. We build sites designed around one question: what do you want a visitor to do, and how quickly can we get them there?',
            'whoTitle' => 'Who this is for',
            'who' => [
                'You have no website, or one built years ago that you cannot update yourself.',
                'People find you on Instagram but you have nowhere to send them that explains what you do.',
                'You need to be found on Google when someone searches for your service in Erbil.',
                'Your customers read Kurdish and Arabic, and your current site only speaks one of them.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'A site in Kurdish, Arabic and English, each properly laid out — right-to-left is designed, not bolted on.',
                'Built to load fast on a phone over 4G, because that is how almost everyone will see it.',
                'Enquiry forms and WhatsApp buttons that reach you the moment someone uses them.',
                'An admin panel where you change text and photos yourself, without calling us.',
                'Google Search Console and analytics set up, so you can see what people actually do.',
                'The full source code and every account credential handed to you at launch.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'We agree the goal',
                    'body' => 'One call to work out who the site is for and what you want them to do. We write it down before anyone designs anything.',
                ],
                [
                    'step' => 'Fixed scope and price',
                    'body' => 'You get a written scope, a price and a date. Neither changes unless you ask for something new.',
                ],
                [
                    'step' => 'Design, then build',
                    'body' => 'You see the design and approve it before we write code. Changes are cheap at this stage and expensive later.',
                ],
                [
                    'step' => 'Launch and hand over',
                    'body' => 'We put it live, show your team how to run it, and give you every credential. You own all of it.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'How much does a website cost?',
                    'a' => 'It depends on how many pages you need and whether it connects to anything else. Tell us roughly what you have in mind and you will get an indicative range within one working day — before any meeting.',
                ],
                [
                    'q' => 'How long does it take?',
                    'a' => 'A straightforward business site is usually a few weeks. We give you a date with the quote and tell you immediately if anything threatens it.',
                ],
                [
                    'q' => 'Can I update it myself?',
                    'a' => 'Yes. Text and photos are editable from an admin panel in your own language. If you can use Facebook, you can use it.',
                ],
                [
                    'q' => 'Who owns the site when it is done?',
                    'a' => 'You do — the code, the content, the domain and every account. Full source and credentials are handed over at launch. Nothing is held back to keep you tied to us.',
                ],
                [
                    'q' => 'What if I already have a website?',
                    'a' => 'We can rebuild it or take over the one you have. Either is fine, and we will tell you honestly which one makes more sense for you.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'تطوير المواقع',
            'title' => 'تصميم وتطوير المواقع في أربيل | GoSoftware',
            'meta' => 'نبني مواقع تجلب الزبائن للشركات العراقية — سريعة، بالكردية والعربية والإنجليزية، وسهلة التحديث على فريقك. تقدير مجاني خلال يوم عمل واحد.',
            'h1' => 'موقع يجلب لك زبائن — لا مجرد بطاقة تعريف على الإنترنت',
            'card' => 'موقع يعمل لصالحك: سريع، يظهر في بحث جوجل، ومكتوب باللغات التي يستخدمها زبائنك فعلاً.',
            'intro' => 'معظم مواقع الشركات في العراق شعار ورقم هاتف وصفحة نصّ لا يقرأها أحد. هذه بطاقة تعريف، والبطاقة لا تبيع شيئاً. نحن نبني مواقع مصمّمة حول سؤال واحد: ماذا تريد من الزائر أن يفعل، وكم بسرعة نوصله إلى ذلك؟',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'ليس لديك موقع، أو لديك موقع بُني قبل سنوات ولا تستطيع تحديثه بنفسك.',
                'يجدك الناس على إنستغرام لكن ليس لديك مكان ترسلهم إليه يشرح ما تقدّمه.',
                'تريد أن تظهر في جوجل حين يبحث أحدهم عن خدمتك في أربيل.',
                'زبائنك يقرؤون الكردية والعربية، وموقعك الحالي يتحدث بواحدة فقط.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'موقع بالكردية والعربية والإنجليزية، كلٌّ منها مصمّم كما ينبغي — الاتجاه من اليمين لليسار مُصمَّم لا مُضاف.',
                'مبني ليُحمَّل بسرعة على الهاتف عبر شبكة 4G، لأن هكذا سيراه الجميع تقريباً.',
                'نماذج تواصل وأزرار واتساب تصلك لحظة استخدامها.',
                'لوحة تحكّم تغيّر منها النصوص والصور بنفسك دون الاتصال بنا.',
                'إعداد Google Search Console والتحليلات، لترى ما يفعله الناس فعلاً.',
                'الشيفرة المصدرية كاملة وكل بيانات الدخول تُسلَّم لك عند الإطلاق.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'نتفق على الهدف',
                    'body' => 'مكالمة واحدة لنعرف لمن الموقع وماذا تريد من الزائر أن يفعل. نكتب ذلك قبل أن يصمّم أحد أي شيء.',
                ],
                [
                    'step' => 'نطاق وسعر ثابتان',
                    'body' => 'تحصل على نطاق مكتوب وسعر وتاريخ. لا يتغيّر أيٌّ منها إلا إذا طلبت شيئاً جديداً.',
                ],
                [
                    'step' => 'التصميم ثم البناء',
                    'body' => 'ترى التصميم وتوافق عليه قبل كتابة أي شيفرة. التعديل رخيص في هذه المرحلة وباهظ بعدها.',
                ],
                [
                    'step' => 'الإطلاق والتسليم',
                    'body' => 'نطلقه، ونُدرّب فريقك على إدارته، ونسلّمك كل بيانات الدخول. كل ذلك ملكك.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'كم تكلفة الموقع؟',
                    'a' => 'يعتمد على عدد الصفحات وما إذا كان يتصل بأنظمة أخرى. أخبرنا بما يدور في ذهنك وستحصل على نطاق سعري استرشادي خلال يوم عمل واحد — قبل أي اجتماع.',
                ],
                [
                    'q' => 'كم يستغرق؟',
                    'a' => 'الموقع التعريفي البسيط عادةً بضعة أسابيع. نعطيك تاريخاً مع العرض ونخبرك فوراً إذا ظهر ما يهدّده.',
                ],
                [
                    'q' => 'هل أستطيع تحديثه بنفسي؟',
                    'a' => 'نعم. النصوص والصور قابلة للتعديل من لوحة تحكّم بلغتك. إن كنت تستخدم فيسبوك فستستخدمها.',
                ],
                [
                    'q' => 'لمن يعود الموقع بعد الانتهاء؟',
                    'a' => 'لك — الشيفرة والمحتوى والنطاق وكل الحسابات. الشيفرة الكاملة وبيانات الدخول تُسلَّم عند الإطلاق. لا نحتفظ بشيء ليبقيك مرتبطاً بنا.',
                ],
                [
                    'q' => 'ماذا لو كان لديّ موقع بالفعل؟',
                    'a' => 'يمكننا إعادة بنائه أو استلام الموجود. كلاهما ممكن، وسنخبرك بصراحة أيّهما أنسب لك.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'دروستکردنی ماڵپەڕ',
            'title' => 'دیزاین و دروستکردنی ماڵپەڕ لە هەولێر | GoSoftware',
            'meta' => 'ماڵپەڕ دروست دەکەین کە کڕیارت بۆ دەهێنێت — خێرا، بە کوردی و عەرەبی و ئینگلیزی، و ئاسان بۆ نوێکردنەوە لەلایەن تیمەکەتەوە. خەمڵاندنی خۆڕایی لە یەک ڕۆژی کاردا.',
            'h1' => 'ماڵپەڕێک کە کڕیارت بۆ دەهێنێت — نەک تەنها کارتێکی ناساندنی ئۆنڵاین',
            'card' => 'ماڵپەڕێک کە بۆ تۆ کار دەکات: خێرا، لە گووگڵ دەدۆزرێتەوە، و بەو زمانانە نووسراوە کە کڕیارەکانت بەکاریدەهێنن.',
            'intro' => 'زۆربەی ماڵپەڕی کۆمپانیاکان لە عێراق لۆگۆیەک و ژمارەیەکی تەلەفۆن و لاپەڕەیەکی دەقن کە کەس ناینوێنێت. ئەمە کارتی ناساندنە، و کارتی ناساندن هیچ نافرۆشێت. ئێمە ماڵپەڕ دروست دەکەین کە لە دەوری یەک پرسیار دیزاین کراون: دەتەوێت سەردانکەر چی بکات، و بە چ خێراییەک بیگەیەنینە ئەوێ؟',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'ماڵپەڕت نییە، یان ماڵپەڕێکت هەیە کە ساڵانێک لەمەوبەر دروستکراوە و ناتوانیت خۆت نوێی بکەیتەوە.',
                'خەڵک لە ئینستاگرام دەتدۆزنەوە بەڵام شوێنێکت نییە بیاننێریتە ئەوێ و ڕوونی بکاتەوە چی دەکەیت.',
                'دەتەوێت لە گووگڵ دەربکەویت کاتێک کەسێک لە هەولێر بەدوای خزمەتگوزارییەکەتدا دەگەڕێت.',
                'کڕیارەکانت کوردی و عەرەبی دەخوێننەوە، و ماڵپەڕی ئێستات تەنها بە یەکێکیان قسە دەکات.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'ماڵپەڕێک بە کوردی و عەرەبی و ئینگلیزی، هەریەکەیان بەدروستی ڕێکخراو — ئاراستەی ڕاست بۆ چەپ دیزاین کراوە، نەک زیادکراو.',
                'دروستکراوە بۆ ئەوەی بە خێرایی لەسەر مۆبایل بە 4G باربکات، چونکە بەم شێوەیە نزیکەی هەموو کەسێک دەیبینێت.',
                'فۆڕمی پەیوەندی و دوگمەی واتسئەپ کە هەر کاتێک بەکاربهێنرێن دەگەنە دەستت.',
                'پانێڵێکی بەڕێوەبردن کە خۆت دەق و وێنەکانی تێدا دەگۆڕیت، بەبێ پەیوەندیکردن بە ئێمەوە.',
                'ڕێکخستنی Google Search Console و شیکاری، بۆ ئەوەی ببینیت خەڵک بەڕاستی چی دەکات.',
                'کۆدی سەرچاوەی تەواو و هەموو زانیاری چوونەژوورەوە لە کاتی دەستپێکردندا پێت دەدرێت.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'لەسەر ئامانج ڕێک دەکەوین',
                    'body' => 'یەک پەیوەندی بۆ ئەوەی بزانین ماڵپەڕەکە بۆ کێیە و دەتەوێت سەردانکەر چی بکات. پێش ئەوەی کەس شتێک دیزاین بکات دەینووسین.',
                ],
                [
                    'step' => 'پێکهاتە و نرخی جێگیر',
                    'body' => 'پێکهاتەیەکی نووسراو و نرخێک و بەروارێک وەردەگریت. هیچیان ناگۆڕێن مەگەر تۆ داوای شتێکی نوێ بکەیت.',
                ],
                [
                    'step' => 'دیزاین، پاشان دروستکردن',
                    'body' => 'دیزاینەکە دەبینیت و پەسەندی دەکەیت پێش ئەوەی کۆد بنووسین. گۆڕانکاری لەم قۆناغەدا ئەرزانە و دواتر گرانە.',
                ],
                [
                    'step' => 'دەستپێکردن و ڕادەستکردن',
                    'body' => 'بەکاریدەخەین، تیمەکەت ڕادەهێنین چۆن بەڕێوەی ببەن، و هەموو زانیارییەکانی چوونەژوورەوەت پێدەدەین. هەمووی هی تۆیە.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'نرخی ماڵپەڕ چەندە؟',
                    'a' => 'بەندە بەوەی چەند لاپەڕەت پێویستە و ئایا پەیوەندی بە شتێکی ترەوە دەکات. پێمان بڵێ چیت لە مێشکدایە و لە یەک ڕۆژی کاردا مەودایەکی نرخی ئاماژەیی وەردەگریت — پێش هەر کۆبوونەوەیەک.',
                ],
                [
                    'q' => 'چەند دەخایەنێت؟',
                    'a' => 'ماڵپەڕێکی ئاسایی کار بە زۆری چەند هەفتەیەکە. بەرواری پێدەدەین لەگەڵ نرخەکە و ئەگەر شتێک مەترسی لەسەر دروست بکات یەکسەر پێت دەڵێین.',
                ],
                [
                    'q' => 'دەتوانم خۆم نوێی بکەمەوە؟',
                    'a' => 'بەڵێ. دەق و وێنەکان لە پانێڵێکی بەڕێوەبردن بە زمانی خۆت دەگۆڕدرێن. ئەگەر بتوانیت فەیسبووک بەکاربهێنیت، دەتوانیت ئەمەش بەکاربهێنیت.',
                ],
                [
                    'q' => 'دوای تەواوبوون ماڵپەڕەکە هی کێیە؟',
                    'a' => 'هی تۆ — کۆد و ناوەڕۆک و دۆمەین و هەموو هەژمارەکان. کۆدی تەواو و زانیاری چوونەژوورەوە لە کاتی دەستپێکردندا ڕادەستت دەکرێن. هیچ شتێک ناهێڵینەوە بۆ ئەوەی بە ئێمەوە بەستراو بمێنیتەوە.',
                ],
                [
                    'q' => 'ئەی ئەگەر ماڵپەڕم هەبێت؟',
                    'a' => 'دەتوانین دووبارە دروستی بکەینەوە یان ئەوەی هەیە وەریبگرین. هەردووکیان دەکرێن، و بە ڕاشکاوی پێت دەڵێین کامەیان بۆ تۆ لۆژیکیترە.',
                ],
            ],
        ],
    ],

    'web-applications' => [
        'tag' => 'WEB APPS',
        'whatsapp' => 'service_web_app',
        'icon' => 'window',
        'en' => [
            'name' => 'Web Applications',
            'title' => 'Custom Web Application Development in Erbil | GoSoftware',
            'meta' => 'Turn how your business works into software your team logs into from anywhere. Custom web apps built in Erbil, in Kurdish, Arabic and English.',
            'h1' => 'Turn the way your business works into software your team can log into from anywhere',
            'card' => 'The process only you run, built into software your staff can use from any branch, any device.',
            'intro' => 'Off-the-shelf software makes you work its way. A web application works your way — the same steps your team already follows, minus the paper, the re-typing and the four people who each keep their own version of the file.',
            'whoTitle' => 'Who this is for',
            'who' => [
                'Your process is genuinely yours, and no product on the market matches it.',
                'Staff work across branches or from home and need the same live information.',
                'You are paying per-seat for software you only use a fraction of.',
                'The work happens in email threads and spreadsheets, and nobody can see the whole picture.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'Software shaped around how you actually work, not how a foreign vendor assumed you do.',
                'Accounts and permissions, so people see what they should and nothing more.',
                'Works in the browser on desktop and phone — nothing to install, nothing to update.',
                'Reports that answer the questions you currently answer by hand.',
                'Full source code and database handed to you. Host it with us or anywhere you like.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'We map what you do now',
                    'body' => 'We sit with the people doing the work and write down the real process, including the parts nobody documented.',
                ],
                [
                    'step' => 'Fixed scope and price',
                    'body' => 'A written scope, a price and a date, agreed before any code exists.',
                ],
                [
                    'step' => 'Built in stages you can see',
                    'body' => 'You get something usable early and give feedback while changing it is still cheap.',
                ],
                [
                    'step' => 'Launch, train, support',
                    'body' => 'We migrate your existing data, train your team, and stay reachable afterwards.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'How much does a custom web app cost?',
                    'a' => 'It varies more than a website, because it depends entirely on how much the software has to do. Describe the process and you will get an indicative range within one working day.',
                ],
                [
                    'q' => 'Can it work with the systems we already have?',
                    'a' => 'Usually. Tell us what you use and we will say plainly whether it can be connected, and what it would take.',
                ],
                [
                    'q' => 'What happens to the data we already have?',
                    'a' => 'We migrate it. Spreadsheets, an old system, or paper — we agree the plan before launch, not after.',
                ],
                [
                    'q' => 'Who owns the code and the data?',
                    'a' => 'You do. Full source and credentials handed over at launch. You can take it to another developer at any time.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'تطبيقات الويب',
            'title' => 'تطوير تطبيقات ويب مخصّصة في أربيل | GoSoftware',
            'meta' => 'حوّل طريقة عمل شركتك إلى برنامج يدخل إليه فريقك من أي مكان. تطبيقات ويب مخصّصة تُبنى في أربيل، بالكردية والعربية والإنجليزية.',
            'h1' => 'حوّل طريقة عمل شركتك إلى برنامج يدخل إليه فريقك من أي مكان',
            'card' => 'العملية التي تديرها أنت وحدك، مبنيّة في برنامج يستخدمه موظفوك من أي فرع وأي جهاز.',
            'intro' => 'البرامج الجاهزة تجبرك على العمل بطريقتها. تطبيق الويب يعمل بطريقتك أنت — الخطوات نفسها التي يتبعها فريقك، ناقص الورق وإعادة الإدخال والأربعة أشخاص الذين يحتفظ كلٌّ منهم بنسخته من الملف.',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'عمليتك خاصة بك فعلاً، ولا يوجد منتج جاهز يطابقها.',
                'موظفوك يعملون بين فروع أو من المنزل ويحتاجون المعلومة نفسها ولحظياً.',
                'تدفع اشتراكاً لكل مستخدم مقابل برنامج تستعمل جزءاً صغيراً منه.',
                'العمل يجري في سلاسل بريد وجداول إكسل، ولا أحد يرى الصورة كاملة.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'برنامج مبنيّ حول طريقة عملك الحقيقية، لا حول ما افترضته شركة أجنبية.',
                'حسابات وصلاحيات، فيرى كل شخص ما يخصّه ولا شيء أكثر.',
                'يعمل في المتصفّح على الحاسوب والهاتف — لا تثبيت ولا تحديثات.',
                'تقارير تجيب عن الأسئلة التي تجيب عنها اليوم يدوياً.',
                'الشيفرة الكاملة وقاعدة البيانات تُسلَّم لك. استضفها معنا أو حيث تشاء.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'نرسم ما تفعلونه الآن',
                    'body' => 'نجلس مع من ينفّذ العمل ونكتب العملية الحقيقية، بما فيها ما لم يوثّقه أحد.',
                ],
                [
                    'step' => 'نطاق وسعر ثابتان',
                    'body' => 'نطاق مكتوب وسعر وتاريخ، يُتَّفق عليها قبل وجود أي شيفرة.',
                ],
                [
                    'step' => 'بناء على مراحل ترونها',
                    'body' => 'تحصل على شيء صالح للاستخدام مبكراً وتعطي ملاحظاتك بينما التعديل ما يزال رخيصاً.',
                ],
                [
                    'step' => 'إطلاق وتدريب ودعم',
                    'body' => 'ننقل بياناتكم الحالية، وندرّب فريقك، ونبقى متاحين بعدها.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'كم تكلفة تطبيق ويب مخصّص؟',
                    'a' => 'يتفاوت أكثر من الموقع، لأنه يعتمد كلياً على حجم ما يجب أن يفعله البرنامج. صف العملية وستحصل على نطاق استرشادي خلال يوم عمل واحد.',
                ],
                [
                    'q' => 'هل يمكن أن يعمل مع أنظمتنا الحالية؟',
                    'a' => 'غالباً نعم. أخبرنا بما تستخدمونه وسنقول بوضوح هل يمكن ربطه وما الذي يتطلّبه ذلك.',
                ],
                [
                    'q' => 'ماذا يحدث لبياناتنا الحالية؟',
                    'a' => 'ننقلها. جداول إكسل أو نظام قديم أو ورق — نتفق على الخطة قبل الإطلاق لا بعده.',
                ],
                [
                    'q' => 'لمن تعود الشيفرة والبيانات؟',
                    'a' => 'لك. الشيفرة الكاملة وبيانات الدخول تُسلَّم عند الإطلاق. تستطيع نقلها لمطوّر آخر متى شئت.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'ئەپی وێب',
            'title' => 'دروستکردنی ئەپی وێبی تایبەت لە هەولێر | GoSoftware',
            'meta' => 'ئەو شێوازە بگۆڕە کە کارەکەت پێی دەڕوات بۆ سۆفتوێرێک کە تیمەکەت لە هەر شوێنێکەوە دەچێتە ژوورەوە. ئەپی وێبی تایبەت لە هەولێر دروست دەکرێت.',
            'h1' => 'ئەو شێوازەی کارەکەت پێی دەڕوات بیکە بە سۆفتوێرێک کە تیمەکەت لە هەر شوێنێکەوە دەچێتە ژوورەوە',
            'card' => 'ئەو پرۆسەیەی تەنها تۆ بەڕێوەی دەبەیت، دروستکراو لە سۆفتوێرێکدا کە کارمەندەکانت لە هەر لقێک و هەر ئامێرێکەوە بەکاریدەهێنن.',
            'intro' => 'سۆفتوێری ئامادە ناچارت دەکات بە شێوازی ئەو کار بکەیت. ئەپی وێب بە شێوازی تۆ کار دەکات — هەمان ئەو هەنگاوانەی تیمەکەت پەیڕەوی دەکات، بەبێ کاغەز و دووبارە نووسینەوە و ئەو چوار کەسەی هەریەکەیان وەشانی خۆی لە فایلەکە هەڵدەگرێت.',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'پرۆسەکەت بەڕاستی هی خۆتە، و هیچ بەرهەمێکی بازاڕ لەگەڵی ناگونجێت.',
                'کارمەندەکانت لە نێوان لقەکان یان لە ماڵەوە کار دەکەن و هەمان زانیاری زیندوویان پێویستە.',
                'بۆ هەر بەکارهێنەرێک پارە دەدەیت بۆ سۆفتوێرێک کە تەنها بەشێکی کەمی بەکاردەهێنیت.',
                'کارەکە لە زنجیرەی ئیمەیڵ و خشتەی ئێکسڵدا ڕوودەدات، و کەس وێنە تەواوەکە نابینێت.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'سۆفتوێرێک کە لە دەوری شێوازی ڕاستەقینەی کارکردنت دروستکراوە، نەک ئەوەی کۆمپانیایەکی بیانی وای دانا.',
                'هەژمار و دەسەڵات، تاکو هەر کەسێک ئەوە ببینێت کە دەبێت و هیچی زیاتر.',
                'لە وێبگەڕدا لەسەر کۆمپیوتەر و مۆبایل کار دەکات — هیچ دامەزراندنێک، هیچ نوێکردنەوەیەک.',
                'ڕاپۆرت کە وەڵامی ئەو پرسیارانە دەداتەوە کە ئێستا بە دەست وەڵامیان دەدەیتەوە.',
                'کۆدی سەرچاوەی تەواو و داتابەیس ڕادەستت دەکرێن. لەلای ئێمە هۆست بکە یان لە هەر شوێنێک کە دەتەوێت.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'نەخشەی ئەوە دەکێشین کە ئێستا دەیکەیت',
                    'body' => 'لەگەڵ ئەو کەسانە دادەنیشین کە کارەکە دەکەن و پرۆسە ڕاستەقینەکە دەنووسین، لەوانەش ئەو بەشانەی کەس تۆماری نەکردوون.',
                ],
                [
                    'step' => 'پێکهاتە و نرخی جێگیر',
                    'body' => 'پێکهاتەیەکی نووسراو و نرخێک و بەروارێک، ڕێککەوتوو پێش ئەوەی هیچ کۆدێک هەبێت.',
                ],
                [
                    'step' => 'بە قۆناغ دروست دەکرێت و دەیبینیت',
                    'body' => 'زوو شتێکی بەکارهێنراو وەردەگریت و ڕا دەدەیت لە کاتێکدا گۆڕینی هێشتا ئەرزانە.',
                ],
                [
                    'step' => 'دەستپێکردن، ڕاهێنان، پشتگیری',
                    'body' => 'داتا ئێستاکەت دەگوازینەوە، تیمەکەت ڕادەهێنین، و دوایی بەردەست دەمێنینەوە.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'نرخی ئەپی وێبی تایبەت چەندە؟',
                    'a' => 'زیاتر لە ماڵپەڕ جیاوازە، چونکە تەواو بەندە بەوەی سۆفتوێرەکە چەند کار دەبێت بکات. پرۆسەکە باس بکە و لە یەک ڕۆژی کاردا مەودایەکی ئاماژەیی وەردەگریت.',
                ],
                [
                    'q' => 'دەتوانێت لەگەڵ ئەو سیستەمانە کار بکات کە هەمانن؟',
                    'a' => 'زۆرجار بەڵێ. پێمان بڵێ چی بەکاردەهێنن و بە ڕاشکاوی دەڵێین ئایا دەکرێت پەیوەست بکرێت و چی دەخوازێت.',
                ],
                [
                    'q' => 'چی بەسەر ئەو داتایە دێت کە هەمانە؟',
                    'a' => 'دەیگوازینەوە. خشتەی ئێکسڵ، سیستەمێکی کۆن، یان کاغەز — پێش دەستپێکردن لەسەر پلانەکە ڕێک دەکەوین، نەک دوای.',
                ],
                [
                    'q' => 'کۆد و داتا هی کێن؟',
                    'a' => 'هی تۆ. کۆدی تەواو و زانیاری چوونەژوورەوە لە کاتی دەستپێکردندا ڕادەستت دەکرێن. هەر کاتێک بتەوێت دەتوانیت بۆ گەشەپێدەرێکی تر بیبەیت.',
                ],
            ],
        ],
    ],

    'mobile-app-development' => [
        'tag' => 'MOBILE',
        'whatsapp' => 'service_mobile',
        'icon' => 'phone',
        'en' => [
            'name' => 'Mobile App Development',
            'title' => 'iPhone & Android App Development in Erbil | GoSoftware',
            'meta' => 'One build, both stores. Native-quality iPhone and Android apps for Iraqi businesses, published under your own developer accounts.',
            'h1' => 'Launch a fast, modern app for iPhone and Android — one build, both stores',
            'card' => 'One codebase, both app stores, published under your accounts — not ours.',
            'intro' => 'Building an iPhone app and an Android app separately costs roughly twice as much and takes twice as long to change. We build once and ship to both stores, so your budget goes into the product rather than into doing the same work twice.',
            'whoTitle' => 'Who this is for',
            'who' => [
                'You have an idea and need to know what it would actually cost before committing.',
                'Your customers already expect an app — delivery, booking, loyalty, ordering.',
                'You have a web system and your staff or customers need it in their pocket.',
                'You have an app someone else abandoned and it needs rescuing.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'One codebase shipping to both the App Store and Google Play.',
                'Published under your own Apple and Google developer accounts — you keep the listing.',
                'Kurdish, Arabic and English, with right-to-left designed in from the start.',
                'Push notifications, and offline behaviour that does not fall over on a weak connection.',
                'Store listing, screenshots and the review process handled — including the Iraqi storefront.',
                'Full source code at launch, so another developer could take over tomorrow.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'Shape the idea',
                    'body' => 'We cut the idea down to what has to be in version one, and tell you what should wait.',
                ],
                [
                    'step' => 'Fixed scope and price',
                    'body' => 'A written scope, a price and a date before development starts.',
                ],
                [
                    'step' => 'Build, with builds you can hold',
                    'body' => 'You get the app on your own phone early and often, not a slideshow of screens.',
                ],
                [
                    'step' => 'Publish and support',
                    'body' => 'We handle both store submissions and stay on hand for the first weeks after launch.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'How much does an app cost, and how long?',
                    'a' => 'It depends entirely on what the app does. Describe it in a line or two and you will get an indicative range and timeline within one working day.',
                ],
                [
                    'q' => 'Do I need separate apps for iPhone and Android?',
                    'a' => 'No. We build one codebase that ships to both stores, which is why it costs far less than building twice.',
                ],
                [
                    'q' => 'Whose name is the app published under?',
                    'a' => 'Yours. It goes on your own Apple and Google developer accounts, so you own the listing, the reviews and the users. We will help you set them up.',
                ],
                [
                    'q' => 'Can you take over an app someone else built?',
                    'a' => 'Often yes. Send us what exists and we will tell you honestly whether it is worth continuing or restarting.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'تطوير تطبيقات الموبايل',
            'title' => 'تطوير تطبيقات آيفون وأندرويد في أربيل | GoSoftware',
            'meta' => 'بناء واحد ومتجران. تطبيقات آيفون وأندرويد بجودة أصلية للشركات العراقية، تُنشر باسم حساباتك أنت.',
            'h1' => 'أطلق تطبيقاً سريعاً وحديثاً لآيفون وأندرويد — بناء واحد، متجران',
            'card' => 'شيفرة واحدة، متجران، ويُنشر باسم حساباتك أنت — لا حساباتنا.',
            'intro' => 'بناء تطبيق آيفون وآخر أندرويد بشكل منفصل يكلّف الضعف تقريباً ويستغرق ضعف الوقت عند أي تعديل. نحن نبني مرة واحدة وننشر في المتجرين، فتذهب ميزانيتك إلى المنتج لا إلى تكرار العمل نفسه مرتين.',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'لديك فكرة وتريد أن تعرف كلفتها الحقيقية قبل الالتزام.',
                'زبائنك يتوقّعون تطبيقاً أصلاً — توصيل أو حجز أو ولاء أو طلبات.',
                'لديك نظام على الويب ويحتاجه موظفوك أو زبائنك في جيوبهم.',
                'لديك تطبيق تخلّى عنه شخص آخر ويحتاج إنقاذاً.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'شيفرة واحدة تُنشر في App Store وGoogle Play معاً.',
                'يُنشر باسم حسابيك في آبل وجوجل — الصفحة تبقى لك.',
                'كردي وعربي وإنجليزي، والاتجاه من اليمين لليسار مُصمَّم منذ البداية.',
                'إشعارات، وسلوك يعمل دون إنترنت ولا ينهار على اتصال ضعيف.',
                'صفحة المتجر واللقطات وعملية المراجعة كلها علينا — بما فيها المتجر العراقي.',
                'الشيفرة الكاملة عند الإطلاق، بحيث يستطيع مطوّر آخر استلامه غداً.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'نُحدِّد شكل الفكرة',
                    'body' => 'نختصر الفكرة إلى ما يجب أن يكون في النسخة الأولى، ونخبرك بما ينبغي تأجيله.',
                ],
                [
                    'step' => 'نطاق وسعر ثابتان',
                    'body' => 'نطاق مكتوب وسعر وتاريخ قبل بدء التطوير.',
                ],
                [
                    'step' => 'بناء بنسخ تجرّبها بيدك',
                    'body' => 'تحصل على التطبيق على هاتفك مبكراً وبشكل متكرّر، لا على عرض شرائح للشاشات.',
                ],
                [
                    'step' => 'نشر ودعم',
                    'body' => 'نتولّى التقديم للمتجرين ونبقى قريبين في الأسابيع الأولى بعد الإطلاق.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'كم يكلّف التطبيق وكم يستغرق؟',
                    'a' => 'يعتمد كلياً على ما يفعله التطبيق. صفه في سطر أو سطرين وستحصل على نطاق ومدّة استرشاديين خلال يوم عمل واحد.',
                ],
                [
                    'q' => 'هل أحتاج تطبيقين منفصلين لآيفون وأندرويد؟',
                    'a' => 'لا. نبني شيفرة واحدة تُنشر في المتجرين، ولهذا تكلفتها أقل بكثير من البناء مرتين.',
                ],
                [
                    'q' => 'باسم من يُنشر التطبيق؟',
                    'a' => 'باسمك. يُنشر على حسابيك في آبل وجوجل، فتملك الصفحة والتقييمات والمستخدمين. وسنساعدك في إنشائهما.',
                ],
                [
                    'q' => 'هل تستطيعون استلام تطبيق بناه غيركم؟',
                    'a' => 'غالباً نعم. أرسل لنا ما هو موجود وسنخبرك بصراحة هل يستحق المتابعة أم إعادة البناء.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'دروستکردنی ئەپی مۆبایل',
            'title' => 'دروستکردنی ئەپی ئایفۆن و ئەندرۆید لە هەولێر | GoSoftware',
            'meta' => 'یەک دروستکردن، هەردوو کۆگا. ئەپی ئایفۆن و ئەندرۆید بە کوالیتی ڕەسەن بۆ کۆمپانیا عێراقییەکان، بە هەژماری خۆت بڵاو دەکرێتەوە.',
            'h1' => 'ئەپێکی خێرا و نوێ بۆ ئایفۆن و ئەندرۆید دەربکە — یەک دروستکردن، هەردوو کۆگا',
            'card' => 'یەک کۆد، هەردوو کۆگای ئەپ، بە هەژماری تۆ بڵاودەکرێتەوە — نەک هی ئێمە.',
            'intro' => 'دروستکردنی ئەپێکی ئایفۆن و ئەپێکی ئەندرۆید بە جیا نزیکەی دوو ئەوەندە تێدەچێت و دوو ئەوەندە کات دەخایەنێت بۆ هەر گۆڕانکارییەک. ئێمە یەک جار دروستی دەکەین و بۆ هەردوو کۆگا دەینێرین، تاکو بودجەکەت بچێتە ناو بەرهەمەکەوە نەک بۆ دووبارەکردنەوەی هەمان کار.',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'بیرۆکەیەکت هەیە و دەتەوێت بزانیت بەڕاستی چەند تێدەچێت پێش ئەوەی پابەند بیت.',
                'کڕیارەکانت پێشتر چاوەڕوانی ئەپێکن — گەیاندن، حجزکردن، دڵسۆزی، داواکردن.',
                'سیستەمێکی وێبت هەیە و کارمەند یان کڕیارەکانت لە گیرفانیاندا پێویستیانە.',
                'ئەپێکت هەیە کە کەسێکی تر بەجێی هێشتووە و پێویستی بە ڕزگارکردنە.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'یەک کۆد کە بۆ App Store و Google Play هەردووکیان دەنێردرێت.',
                'بە هەژماری گەشەپێدەری ئەپڵ و گووگڵی خۆت بڵاودەکرێتەوە — لیستەکە هی تۆ دەمێنێتەوە.',
                'کوردی و عەرەبی و ئینگلیزی، بە ئاراستەی ڕاست بۆ چەپ لە سەرەتاوە دیزاینکراو.',
                'ئاگادارکردنەوە، و ڕەفتارێک بێ ئینتەرنێت کە لەسەر پەیوەندی لاواز ناکەوێت.',
                'لیستەی کۆگا و وێنەکان و پرۆسەی پێداچوونەوە هەمووی لەسەر ئێمە — لەوانەش کۆگای عێراق.',
                'کۆدی سەرچاوەی تەواو لە کاتی دەستپێکردندا، تاکو گەشەپێدەرێکی تر سبەینێ بتوانێت وەریبگرێت.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'شێوەی بیرۆکەکە دیاری دەکەین',
                    'body' => 'بیرۆکەکە کورت دەکەینەوە بۆ ئەوەی دەبێت لە وەشانی یەکەمدا بێت، و پێت دەڵێین چی دەبێت چاوەڕێ بکات.',
                ],
                [
                    'step' => 'پێکهاتە و نرخی جێگیر',
                    'body' => 'پێکهاتەیەکی نووسراو و نرخێک و بەروارێک پێش دەستپێکردنی گەشەپێدان.',
                ],
                [
                    'step' => 'دروستکردن، بە وەشانێک کە بەدەستەوەی دەگریت',
                    'body' => 'زوو و بەردەوام ئەپەکە لەسەر مۆبایلی خۆت وەردەگریت، نەک نمایشێکی وێنەی شاشەکان.',
                ],
                [
                    'step' => 'بڵاوکردنەوە و پشتگیری',
                    'body' => 'ناردنی هەردوو کۆگا لەسەر ئێمەیە و لە هەفتەکانی یەکەمی دوای دەستپێکردندا بەردەست دەبین.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'ئەپ چەند تێدەچێت و چەند دەخایەنێت؟',
                    'a' => 'تەواو بەندە بەوەی ئەپەکە چی دەکات. بە یەک دوو دێڕ باسی بکە و لە یەک ڕۆژی کاردا مەودای نرخ و کات وەردەگریت.',
                ],
                [
                    'q' => 'پێویستم بە دوو ئەپی جیاواز هەیە بۆ ئایفۆن و ئەندرۆید؟',
                    'a' => 'نەخێر. یەک کۆد دروست دەکەین کە بۆ هەردوو کۆگا دەنێردرێت، بۆیە زۆر کەمتر تێدەچێت لە دروستکردنی دوو جار.',
                ],
                [
                    'q' => 'ئەپەکە بە ناوی کێ بڵاودەکرێتەوە؟',
                    'a' => 'بە ناوی تۆ. لەسەر هەژماری گەشەپێدەری ئەپڵ و گووگڵی خۆت دەچێت، بۆیە لیستە و هەڵسەنگاندن و بەکارهێنەرەکان هی تۆن. یارمەتیت دەدەین دروستیان بکەیت.',
                ],
                [
                    'q' => 'دەتوانن ئەپێک وەربگرن کە کەسێکی تر دروستی کردووە؟',
                    'a' => 'زۆرجار بەڵێ. ئەوەی هەیە بۆمان بنێرە و بە ڕاشکاوی پێت دەڵێین ئایا شایەنی بەردەوامبوونە یان دەستپێکردنەوە.',
                ],
            ],
        ],
    ],

    'management-systems' => [
        'tag' => 'SYSTEMS',
        'whatsapp' => 'service_system',
        'icon' => 'grid',
        'en' => [
            'name' => 'Management Systems',
            'title' => 'Custom Management Systems for Iraqi Businesses | GoSoftware',
            'meta' => 'Replace spreadsheets and WhatsApp groups with a system built for how your business actually runs. Custom management software from Erbil.',
            'h1' => 'Replace your spreadsheets with a system built for exactly how your business runs',
            'card' => 'Sales, stock, staff and orders in one place — and numbers you can trust without spending three days assembling them.',
            'intro' => 'Sales in one Excel file. Stock in another. Orders in a WhatsApp group. Staff hours on paper. Every month you spend days pulling numbers together — and you still cannot answer simple questions about your own business. A management system is how that stops.',
            'whoTitle' => 'Who this is for',
            'who' => [
                'Your numbers live in several spreadsheets and only one person really understands them.',
                'You cannot answer "what did we sell last month, by branch" without a day of work.',
                'Orders, approvals or shift handovers happen in WhatsApp and get lost.',
                'You have grown past the point where everyone can just remember.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'One place for the things you currently keep in separate files.',
                'Roles and permissions, so branch staff, managers and owners each see the right slice.',
                'The reports you actually ask for, generated instead of assembled.',
                'Your existing spreadsheet data migrated in, not left behind.',
                'Kurdish, Arabic and English, so nobody is locked out by language.',
                'Full source code and database handed over at launch.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'We learn the business',
                    'body' => 'We sit with the people doing the work. The real process is never the one on the org chart.',
                ],
                [
                    'step' => 'Fixed scope and price',
                    'body' => 'Written scope, price and date before anything is built.',
                ],
                [
                    'step' => 'Built in stages',
                    'body' => 'The part that hurts most gets built first, so you feel the benefit before the project ends.',
                ],
                [
                    'step' => 'Migrate, train, support',
                    'body' => 'We bring your data across, train each role, and stay reachable afterwards.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'How much does a management system cost?',
                    'a' => 'It depends on how much of the business it covers. Tell us what you run on now and roughly how many staff, and you will get an indicative range within one working day.',
                ],
                [
                    'q' => 'We use Excel for everything. Can you import it?',
                    'a' => 'Yes, and we would expect to. Messy spreadsheets are normal — we clean and import them as part of the project.',
                ],
                [
                    'q' => 'What if we need changes after launch?',
                    'a' => 'You will, and that is expected. Small changes are quick; anything larger is quoted before we start, never billed as a surprise.',
                ],
                [
                    'q' => 'Can our staff actually use it?',
                    'a' => 'That is the main design constraint. It is in their language, and we train each role rather than handing over a manual.',
                ],
                [
                    'q' => 'Who owns the system and the data?',
                    'a' => 'You do. Full source, database and credentials handed over at launch.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'أنظمة الإدارة',
            'title' => 'أنظمة إدارة مخصّصة للشركات العراقية | GoSoftware',
            'meta' => 'استبدل جداول إكسل ومجموعات واتساب بنظام مبنيّ على طريقة عمل شركتك الحقيقية. برمجيات إدارة مخصّصة من أربيل.',
            'h1' => 'استبدل جداولك بنظام مبنيّ تماماً على طريقة عمل شركتك',
            'card' => 'المبيعات والمخزون والموظفون والطلبات في مكان واحد — وأرقام تثق بها دون ثلاثة أيام من التجميع.',
            'intro' => 'المبيعات في ملف إكسل. المخزون في ملف آخر. الطلبات في مجموعة واتساب. ساعات الموظفين على الورق. كل شهر تقضي أياماً في تجميع الأرقام — ومع ذلك لا تستطيع الإجابة عن أسئلة بسيطة عن عملك أنت. نظام الإدارة هو ما يوقف ذلك.',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'أرقامك موزّعة على عدة جداول ولا يفهمها حقاً إلا شخص واحد.',
                'لا تستطيع الإجابة عن "كم بعنا الشهر الماضي، حسب الفرع" دون يوم عمل كامل.',
                'الطلبات والموافقات وتسليم الورديات تجري في واتساب وتضيع.',
                'كبرت الشركة إلى ما بعد النقطة التي يكفي فيها أن يتذكّر الجميع.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'مكان واحد لما تحتفظ به اليوم في ملفات متفرّقة.',
                'أدوار وصلاحيات، فيرى موظف الفرع والمدير والمالك كلٌّ ما يخصّه.',
                'التقارير التي تطلبها فعلاً، تُولَّد بدل أن تُجمَّع.',
                'بيانات جداولك الحالية تُنقَل، لا تُترَك خلفك.',
                'كردي وعربي وإنجليزي، فلا يُستبعَد أحد بسبب اللغة.',
                'الشيفرة الكاملة وقاعدة البيانات تُسلَّم عند الإطلاق.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'نتعلّم طبيعة العمل',
                    'body' => 'نجلس مع من ينفّذ العمل. العملية الحقيقية ليست أبداً تلك المرسومة في الهيكل الإداري.',
                ],
                [
                    'step' => 'نطاق وسعر ثابتان',
                    'body' => 'نطاق مكتوب وسعر وتاريخ قبل بناء أي شيء.',
                ],
                [
                    'step' => 'البناء على مراحل',
                    'body' => 'الجزء الأكثر إيلاماً يُبنى أولاً، فتشعر بالفائدة قبل انتهاء المشروع.',
                ],
                [
                    'step' => 'نقل وتدريب ودعم',
                    'body' => 'ننقل بياناتك، وندرّب كل دور، ونبقى متاحين بعدها.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'كم يكلّف نظام الإدارة؟',
                    'a' => 'يعتمد على حجم ما يغطّيه من العمل. أخبرنا بما تعمل عليه الآن وكم عدد الموظفين تقريباً، وستحصل على نطاق استرشادي خلال يوم عمل واحد.',
                ],
                [
                    'q' => 'نستخدم إكسل في كل شيء. هل يمكنكم استيراده؟',
                    'a' => 'نعم، ونتوقّع ذلك. الجداول الفوضوية أمر طبيعي — ننظّفها ونستوردها كجزء من المشروع.',
                ],
                [
                    'q' => 'ماذا لو احتجنا تعديلات بعد الإطلاق؟',
                    'a' => 'ستحتاج، وهذا متوقّع. التعديلات الصغيرة سريعة؛ وأي شيء أكبر يُسعَّر قبل البدء، ولا يُفاجئك في الفاتورة.',
                ],
                [
                    'q' => 'هل يستطيع موظفونا استخدامه فعلاً؟',
                    'a' => 'هذا هو القيد التصميمي الأول. النظام بلغتهم، وندرّب كل دور بدل تسليم دليل استخدام.',
                ],
                [
                    'q' => 'لمن يعود النظام والبيانات؟',
                    'a' => 'لك. الشيفرة الكاملة وقاعدة البيانات وبيانات الدخول تُسلَّم عند الإطلاق.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'سیستەمی بەڕێوەبردن',
            'title' => 'سیستەمی بەڕێوەبردنی تایبەت بۆ کۆمپانیا عێراقییەکان | GoSoftware',
            'meta' => 'خشتەی ئێکسڵ و گرووپی واتسئەپ بگۆڕە بە سیستەمێک کە بۆ شێوازی ڕاستەقینەی کارەکەت دروستکراوە. سۆفتوێری بەڕێوەبردنی تایبەت لە هەولێرەوە.',
            'h1' => 'خشتەکانت بگۆڕە بە سیستەمێک کە تەواو بۆ شێوازی کارکردنی کارەکەت دروستکراوە',
            'card' => 'فرۆشتن و کۆگا و کارمەند و داواکاری لە یەک شوێندا — و ژمارەیەک کە متمانەی پێدەکەیت بەبێ سێ ڕۆژ کۆکردنەوە.',
            'intro' => 'فرۆشتن لە فایلێکی ئێکسڵدا. کۆگا لە فایلێکی تردا. داواکارییەکان لە گرووپێکی واتسئەپدا. کاتژمێری کارمەندان لەسەر کاغەز. هەموو مانگێک ڕۆژانێک بەسەر کۆکردنەوەی ژمارەکاندا دەبەیت — و هێشتا ناتوانیت وەڵامی پرسیارە سادەکان دەربارەی کارەکەی خۆت بدەیتەوە. سیستەمی بەڕێوەبردن ئەوەیە کە کۆتایی بەمە دەهێنێت.',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'ژمارەکانت لە چەند خشتەیەکدا بڵاون و تەنها یەک کەس بەڕاستی لێیان دەگات.',
                'ناتوانیت وەڵامی «مانگی ڕابردوو چیمان فرۆشت، بەپێی لق» بدەیتەوە بەبێ ڕۆژێک کار.',
                'داواکاری و ڕەزامەندی و ئاڵوگۆڕی نۆرە لە واتسئەپدا ڕوودەدەن و ون دەبن.',
                'گەورەتر بوویت لەو خاڵەی کە بەسە هەموو کەس بیری بێت.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'یەک شوێن بۆ ئەو شتانەی ئێستا لە فایلی جیاجیادا هەڵیاندەگریت.',
                'ڕۆڵ و دەسەڵات، تاکو کارمەندی لق و بەڕێوەبەر و خاوەن هەریەکەیان بەشی خۆیان ببینن.',
                'ئەو ڕاپۆرتانەی بەڕاستی داوایان دەکەیت، دروست دەکرێن نەک کۆدەکرێنەوە.',
                'داتای خشتەکانی ئێستات دەگوازرێنەوە، بەجێ ناهێڵدرێن.',
                'کوردی و عەرەبی و ئینگلیزی، تاکو کەس بەهۆی زمانەوە دەرنەخرێت.',
                'کۆدی سەرچاوەی تەواو و داتابەیس لە کاتی دەستپێکردندا ڕادەست دەکرێن.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'فێری کارەکە دەبین',
                    'body' => 'لەگەڵ ئەو کەسانە دادەنیشین کە کارەکە دەکەن. پرۆسە ڕاستەقینەکە هەرگیز ئەوە نییە کە لە خشتەی ڕێکخستندایە.',
                ],
                [
                    'step' => 'پێکهاتە و نرخی جێگیر',
                    'body' => 'پێکهاتەی نووسراو و نرخ و بەروار پێش دروستکردنی هیچ شتێک.',
                ],
                [
                    'step' => 'بە قۆناغ دروست دەکرێت',
                    'body' => 'ئەو بەشەی زۆرترین ئازار دەدات یەکەم دروست دەکرێت، تاکو پێش کۆتایی پرۆژەکە سوودەکە هەست پێبکەیت.',
                ],
                [
                    'step' => 'گواستنەوە، ڕاهێنان، پشتگیری',
                    'body' => 'داتاکەت دەهێنینە ئەوێ، هەر ڕۆڵێک ڕادەهێنین، و دوایی بەردەست دەمێنینەوە.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'سیستەمی بەڕێوەبردن چەند تێدەچێت؟',
                    'a' => 'بەندە بەوەی چەند بەشی کارەکە دەگرێتەوە. پێمان بڵێ ئێستا لەسەر چی کار دەکەیت و نزیکەی چەند کارمەندت هەیە، و لە یەک ڕۆژی کاردا مەودایەکی ئاماژەیی وەردەگریت.',
                ],
                [
                    'q' => 'بۆ هەموو شتێک ئێکسڵ بەکاردەهێنین. دەتوانن هاوردەی بکەن؟',
                    'a' => 'بەڵێ، و چاوەڕوانیشی دەکەین. خشتەی شێواو ئاساییە — وەک بەشێک لە پرۆژەکە پاکیان دەکەینەوە و هاوردەیان دەکەین.',
                ],
                [
                    'q' => 'ئەی ئەگەر دوای دەستپێکردن گۆڕانکاریمان پێویست بێت؟',
                    'a' => 'پێویستت دەبێت، و ئەمە چاوەڕوانکراوە. گۆڕانکاری بچووک خێران؛ هەرچی گەورەتر بێت پێش دەستپێکردن نرخی دیاری دەکرێت، هەرگیز وەک سەرسوڕمانێک لە پسووڵەدا نایەت.',
                ],
                [
                    'q' => 'کارمەندەکانمان بەڕاستی دەتوانن بەکاریبهێنن؟',
                    'a' => 'ئەمە یەکەم سنووری دیزاینە. بە زمانی خۆیانە، و هەر ڕۆڵێک ڕادەهێنین لەبری ئەوەی ڕێنمایینامەیەکیان بدەینێ.',
                ],
                [
                    'q' => 'سیستەم و داتا هی کێن؟',
                    'a' => 'هی تۆ. کۆدی تەواو و داتابەیس و زانیاری چوونەژوورەوە لە کاتی دەستپێکردندا ڕادەست دەکرێن.',
                ],
            ],
        ],
    ],

    'pos-inventory' => [
        'tag' => 'POS',
        'whatsapp' => 'service_pos',
        'icon' => 'till',
        'en' => [
            'name' => 'POS & Inventory',
            'title' => 'POS and Inventory Systems in Iraq — Works Offline | GoSoftware',
            'meta' => 'Point of sale and stock control for shops, restaurants and pharmacies across Iraq. Keeps selling when the internet drops, and syncs when it returns.',
            'h1' => 'POS and stock control for shops, restaurants and pharmacies in Iraq — works offline',
            'card' => 'Sell, print and track stock even when the internet is down. It syncs the moment the connection returns.',
            'intro' => 'The internet goes down. That is not a rare event here, it is a Tuesday. Most point-of-sale software treats it as an emergency and stops taking money. Ours treats it as normal: the till keeps selling, receipts keep printing, stock keeps counting, and everything syncs the moment the connection is back.',
            'whoTitle' => 'Who this is for',
            'who' => [
                'A shop, restaurant, café or pharmacy where a queue cannot wait for the internet.',
                'Several branches whose stock and takings you want to see in one place.',
                'You count stock by hand and discover what is missing weeks later.',
                'Your current till is a cash drawer and a notebook, or software nobody supports any more.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'Offline-first selling. The till never stops because the line dropped, and syncs automatically afterwards.',
                'Stock that updates as you sell, with low-stock alerts before you run out.',
                'Multiple branches and terminals, with takings and stock visible per branch and in total.',
                'Receipt printing, barcode scanning and cash-drawer support with the hardware shops here actually use.',
                'Staff accounts with shift reports, so you know who sold what.',
                'Kurdish, Arabic and English on the till screen.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'We visit and watch',
                    'body' => 'We come to the shop and watch a busy hour. How you actually sell decides how the till should work.',
                ],
                [
                    'step' => 'Fixed scope and price',
                    'body' => 'Written scope, price per terminal and a date, agreed up front.',
                ],
                [
                    'step' => 'Install and load stock',
                    'body' => 'We set up the terminals, import your product list and print your barcodes.',
                ],
                [
                    'step' => 'Train on the floor',
                    'body' => 'We train your staff during real trading, not in a meeting room, and stay reachable for the first weeks.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'What actually happens when the internet goes down?',
                    'a' => 'Nothing changes at the till. Sales, receipts and stock all continue on the terminal itself, and everything syncs to the other branches as soon as the connection is back. This is the part most systems here get wrong.',
                ],
                [
                    'q' => 'How much does it cost — per shop or per terminal?',
                    'a' => 'Pricing is per terminal, with the central system charged once. Tell us how many branches and terminals you have and you will get a figure within one working day.',
                ],
                [
                    'q' => 'Can it work with our existing barcode scanner and printer?',
                    'a' => 'Usually yes. Tell us what you have and we will confirm before you buy anything new.',
                ],
                [
                    'q' => 'Can I see all my branches in one place?',
                    'a' => 'Yes — takings, stock and staff performance per branch and combined, from your phone.',
                ],
                [
                    'q' => 'Can you import our product list?',
                    'a' => 'Yes. Excel, an old system, or a printed list — we get it in as part of the setup.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'نقاط البيع والمخزون',
            'title' => 'أنظمة نقاط بيع ومخزون في العراق — تعمل دون إنترنت | GoSoftware',
            'meta' => 'نقاط بيع وإدارة مخزون للمحلات والمطاعم والصيدليات في العراق. تواصل البيع عند انقطاع الإنترنت، وتُزامن فور عودته.',
            'h1' => 'نقاط بيع وإدارة مخزون للمحلات والمطاعم والصيدليات في العراق — تعمل دون إنترنت',
            'card' => 'بِع واطبع وتابع المخزون حتى وقت انقطاع الإنترنت. تتم المزامنة لحظة عودة الاتصال.',
            'intro' => 'الإنترنت ينقطع. هذا ليس حدثاً نادراً هنا، بل يوم عادي. معظم برامج نقاط البيع تتعامل معه كطارئ وتتوقف عن قبض المال. نظامنا يتعامل معه كأمر طبيعي: الكاشير يواصل البيع، والإيصالات تُطبع، والمخزون يُحتسب، وكل شيء يُزامَن فور عودة الاتصال.',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'محل أو مطعم أو مقهى أو صيدلية، حيث لا يمكن للطابور أن ينتظر الإنترنت.',
                'عدة فروع تريد أن ترى مخزونها ومبيعاتها في مكان واحد.',
                'تجرد المخزون يدوياً وتكتشف النواقص بعد أسابيع.',
                'الكاشير لديك درج نقود ودفتر، أو برنامج لم يعد أحد يدعمه.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'بيع يعمل دون إنترنت أولاً. الكاشير لا يتوقف لأن الخط انقطع، والمزامنة تتم تلقائياً بعدها.',
                'مخزون يتحدّث مع كل عملية بيع، مع تنبيهات قبل أن ينفد.',
                'فروع وأجهزة متعدّدة، مع رؤية المبيعات والمخزون لكل فرع وللمجموع.',
                'طباعة إيصالات ومسح باركود ودعم درج النقود، مع الأجهزة المستخدمة فعلاً هنا.',
                'حسابات للموظفين وتقارير ورديات، فتعرف من باع ماذا.',
                'كردي وعربي وإنجليزي على شاشة الكاشير.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'نزور ونراقب',
                    'body' => 'نأتي إلى المحل ونراقب ساعة ازدحام. طريقة بيعك الحقيقية هي ما يحدّد شكل الكاشير.',
                ],
                [
                    'step' => 'نطاق وسعر ثابتان',
                    'body' => 'نطاق مكتوب وسعر لكل جهاز وتاريخ، يُتَّفق عليها مسبقاً.',
                ],
                [
                    'step' => 'التركيب وتحميل المخزون',
                    'body' => 'نجهّز الأجهزة، ونستورد قائمة منتجاتك، ونطبع الباركودات.',
                ],
                [
                    'step' => 'تدريب أثناء العمل',
                    'body' => 'ندرّب موظفيك أثناء البيع الفعلي لا في غرفة اجتماعات، ونبقى متاحين في الأسابيع الأولى.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'ماذا يحدث فعلاً عند انقطاع الإنترنت؟',
                    'a' => 'لا شيء يتغيّر عند الكاشير. البيع والإيصالات والمخزون تستمر على الجهاز نفسه، وكل شيء يُزامَن مع بقية الفروع فور عودة الاتصال. هذه بالضبط النقطة التي تخفق فيها معظم الأنظمة هنا.',
                ],
                [
                    'q' => 'كم التكلفة — لكل محل أم لكل جهاز؟',
                    'a' => 'التسعير لكل جهاز، والنظام المركزي يُحتسب مرة واحدة. أخبرنا بعدد الفروع والأجهزة وستحصل على رقم خلال يوم عمل واحد.',
                ],
                [
                    'q' => 'هل يعمل مع قارئ الباركود والطابعة الموجودين لدينا؟',
                    'a' => 'غالباً نعم. أخبرنا بما لديك وسنؤكّد قبل أن تشتري أي شيء جديد.',
                ],
                [
                    'q' => 'هل أستطيع رؤية كل فروعي في مكان واحد؟',
                    'a' => 'نعم — المبيعات والمخزون وأداء الموظفين لكل فرع ومجتمعة، من هاتفك.',
                ],
                [
                    'q' => 'هل تستطيعون استيراد قائمة منتجاتنا؟',
                    'a' => 'نعم. إكسل أو نظام قديم أو قائمة مطبوعة — ندخلها كجزء من التركيب.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'سیستەمی فرۆشتن و کۆگا',
            'title' => 'سیستەمی فرۆشتن و کۆگا لە عێراق — بێ ئینتەرنێت کار دەکات | GoSoftware',
            'meta' => 'سیستەمی فرۆشتن و کۆنترۆڵی کۆگا بۆ دوکان و چێشتخانە و دەرمانخانەکانی عێراق. کاتێک ئینتەرنێت دەبڕێت بەردەوام دەفرۆشێت، و کاتێک دەگەڕێتەوە هاوکات دەبێت.',
            'h1' => 'سیستەمی فرۆشتن و کۆنترۆڵی کۆگا بۆ دوکان و چێشتخانە و دەرمانخانەکانی عێراق — بێ ئینتەرنێت کار دەکات',
            'card' => 'بفرۆشە و چاپ بکە و کۆگا بەدواداچوون بکە تەنانەت کاتێک ئینتەرنێت نەماوە. هەر کاتێک پەیوەندی گەڕایەوە هاوکات دەبێت.',
            'intro' => 'ئینتەرنێت دەبڕێت. لێرە ئەمە ڕووداوێکی دەگمەن نییە، ڕۆژێکی ئاساییە. زۆربەی سۆفتوێری فرۆشتن وەک فریاکەوتنێکی لەگەڵدا هەڵسوکەوت دەکات و لە وەرگرتنی پارە دەوەستێت. هی ئێمە وەک شتێکی ئاسایی دەیبینێت: دەزگای فرۆشتن بەردەوام دەفرۆشێت، پسووڵە چاپ دەکرێت، کۆگا ژماردن بەردەوام دەبێت، و هەموو شتێک هەر کاتێک پەیوەندی گەڕایەوە هاوکات دەبێت.',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'دوکان یان چێشتخانە یان کافێ یان دەرمانخانەیەک کە ڕیزەکە ناتوانێت چاوەڕێی ئینتەرنێت بکات.',
                'چەند لقێک کە دەتەوێت کۆگا و داهاتیان لە یەک شوێندا ببینیت.',
                'کۆگا بە دەست دەژمێریت و چەند هەفتەیەک دواتر دەزانیت چی کەمە.',
                'دەزگای فرۆشتنی ئێستات دەفتەرێک و سندوقێکی پارەیە، یان سۆفتوێرێک کە کەس چیتر پشتگیری ناکات.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'فرۆشتنی بێ ئینتەرنێت لە پێشەوە. دەزگاکە هەرگیز ناوەستێت چونکە هێڵەکە بڕاوە، و دواتر خۆکارانە هاوکات دەبێت.',
                'کۆگایەک کە لەگەڵ هەر فرۆشتنێک نوێ دەبێتەوە، بە ئاگادارکردنەوە پێش ئەوەی تەواو بێت.',
                'چەند لق و چەند دەزگا، بە بینینی داهات و کۆگا بۆ هەر لقێک و بە کۆی گشتی.',
                'چاپکردنی پسووڵە و خوێندنەوەی بارکۆد و پشتگیری سندوقی پارە، لەگەڵ ئەو ئامێرانەی لێرە بەکاردەهێنرێن.',
                'هەژماری کارمەندان و ڕاپۆرتی نۆرە، تاکو بزانیت کێ چی فرۆشتووە.',
                'کوردی و عەرەبی و ئینگلیزی لەسەر شاشەی فرۆشتن.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'سەردان دەکەین و تەماشا دەکەین',
                    'body' => 'دێینە دوکانەکە و کاتژمێرێکی قەرەباڵغ تەماشا دەکەین. شێوازی ڕاستەقینەی فرۆشتنت دیاری دەکات دەزگاکە چۆن کار بکات.',
                ],
                [
                    'step' => 'پێکهاتە و نرخی جێگیر',
                    'body' => 'پێکهاتەی نووسراو و نرخ بۆ هەر دەزگایەک و بەروارێک، پێشوەخت ڕێککەوتوو.',
                ],
                [
                    'step' => 'دامەزراندن و بارکردنی کۆگا',
                    'body' => 'دەزگاکان ڕێک دەخەین، لیستی بەرهەمەکانت هاوردە دەکەین و بارکۆدەکانت چاپ دەکەین.',
                ],
                [
                    'step' => 'ڕاهێنان لەسەر شوێنی کار',
                    'body' => 'کارمەندەکانت لە کاتی فرۆشتنی ڕاستەقینەدا ڕادەهێنین، نەک لە ژووری کۆبوونەوە، و لە هەفتەکانی یەکەمدا بەردەست دەمێنینەوە.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'کاتێک ئینتەرنێت دەبڕێت بەڕاستی چی ڕوودەدات؟',
                    'a' => 'هیچ شتێک لەسەر دەزگای فرۆشتن ناگۆڕێت. فرۆشتن و پسووڵە و کۆگا هەموویان لەسەر خودی دەزگاکە بەردەوام دەبن، و هەر کاتێک پەیوەندی گەڕایەوە هەموو شتێک لەگەڵ لقەکانی تر هاوکات دەبێت. ئەمە ئەو بەشەیە کە زۆربەی سیستەمەکانی لێرە هەڵەی تێدا دەکەن.',
                ],
                [
                    'q' => 'چەند تێدەچێت — بۆ هەر دوکانێک یان هەر دەزگایەک؟',
                    'a' => 'نرخ بۆ هەر دەزگایەکە، و سیستەمی ناوەندی یەک جار حیساب دەکرێت. پێمان بڵێ چەند لق و چەند دەزگات هەیە و لە یەک ڕۆژی کاردا ژمارەیەک وەردەگریت.',
                ],
                [
                    'q' => 'لەگەڵ خوێنەری بارکۆد و پرینتەری ئێستامان کار دەکات؟',
                    'a' => 'زۆرجار بەڵێ. پێمان بڵێ چیت هەیە و پێش ئەوەی شتێکی نوێ بکڕیت دڵنیایت دەکەینەوە.',
                ],
                [
                    'q' => 'دەتوانم هەموو لقەکانم لە یەک شوێندا ببینم؟',
                    'a' => 'بەڵێ — داهات و کۆگا و کارایی کارمەندان بۆ هەر لقێک و بە کۆی گشتی، لە مۆبایلەکەتەوە.',
                ],
                [
                    'q' => 'دەتوانن لیستی بەرهەمەکانمان هاوردە بکەن؟',
                    'a' => 'بەڵێ. ئێکسڵ، سیستەمێکی کۆن، یان لیستێکی چاپکراو — وەک بەشێک لە دامەزراندنەکە دەیهێنینە ژوورەوە.',
                ],
            ],
        ],
    ],

    'ecommerce' => [
        'tag' => 'ECOMMERCE',
        'whatsapp' => 'service_ecommerce',
        'icon' => 'bag',
        'en' => [
            'name' => 'E-commerce',
            'title' => 'E-commerce Websites for Iraq — Local Payment & Delivery | GoSoftware',
            'meta' => 'Sell online in Iraq with the payment and delivery methods your customers actually use: cash on delivery, local wallets and local couriers.',
            'h1' => 'Sell online in Iraq — with the payment and delivery methods your customers actually use',
            'card' => 'An online shop built for this market: cash on delivery, local wallets, local couriers, Kurdish and Arabic.',
            'intro' => 'A foreign shop platform assumes a credit card and a postal address. Neither is how most orders in Iraq get paid for or delivered. An online shop only works here if it handles cash on delivery, the wallets people actually have, and the couriers who actually turn up.',
            'whoTitle' => 'Who this is for',
            'who' => [
                'You sell through Instagram DMs and cannot keep up with the orders.',
                'You have a shop and want the same stock selling online without counting it twice.',
                'You tried a foreign platform and it could not do cash on delivery properly.',
                'You need the shop in Kurdish and Arabic, not just English.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'Cash on delivery handled properly — confirmation, courier handover and reconciliation, not just an order note.',
                'Local payment options wired in, and the integration confirmed with you before we promise it publicly.',
                'Courier integration so orders leave as dispatches rather than as WhatsApp messages.',
                'Stock shared with your shop floor if you have one, so online and in-store never disagree.',
                'Kurdish, Arabic and English, with right-to-left product pages designed properly.',
                'An orders dashboard your staff can run without you.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'Work out how you sell now',
                    'body' => 'How orders arrive, how they get paid for, how they get delivered. The shop is built around that.',
                ],
                [
                    'step' => 'Fixed scope and price',
                    'body' => 'Written scope, price and date, including which payment and delivery integrations are in.',
                ],
                [
                    'step' => 'Build and load the catalogue',
                    'body' => 'We build the shop and get your products, photos and prices into it.',
                ],
                [
                    'step' => 'Test with real orders',
                    'body' => 'We run real orders end to end — payment and courier — before you announce it.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'Can you do cash on delivery?',
                    'a' => 'Yes, and properly: order confirmation, courier handover, and reconciling what the courier collected against what you are owed. Most platforms treat it as an afterthought.',
                ],
                [
                    'q' => 'Which online payment methods can you connect?',
                    'a' => 'Tell us which providers you already have a merchant account with and we will confirm exactly what we can integrate before anything is promised. We do not list an integration we have not built.',
                ],
                [
                    'q' => 'Can it use my usual delivery company?',
                    'a' => 'Often yes — it depends on whether they offer an integration. Tell us who you use and we will check before you commit.',
                ],
                [
                    'q' => 'Will online orders mess up my shop stock?',
                    'a' => 'Not if we connect the two. If you also run a POS, online and in-store share one stock count.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'المتاجر الإلكترونية',
            'title' => 'متاجر إلكترونية للعراق — دفع وتوصيل محلي | GoSoftware',
            'meta' => 'بِع أونلاين في العراق بوسائل الدفع والتوصيل التي يستخدمها زبائنك فعلاً: الدفع عند الاستلام والمحافظ المحلية وشركات التوصيل المحلية.',
            'h1' => 'بِع أونلاين في العراق — بوسائل الدفع والتوصيل التي يستخدمها زبائنك فعلاً',
            'card' => 'متجر إلكتروني مبنيّ لهذا السوق: دفع عند الاستلام، محافظ محلية، توصيل محلي، بالكردية والعربية.',
            'intro' => 'المنصّات الأجنبية تفترض بطاقة ائتمان وعنواناً بريدياً. ولا واحد منهما هو الطريقة التي تُدفَع وتُسلَّم بها معظم الطلبات في العراق. المتجر الإلكتروني لا ينجح هنا إلا إذا تعامل مع الدفع عند الاستلام، والمحافظ التي يملكها الناس فعلاً، وشركات التوصيل التي تصل فعلاً.',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'تبيع عبر رسائل إنستغرام ولم تعد تلاحق الطلبات.',
                'لديك محل وتريد بيع المخزون نفسه أونلاين دون جرده مرتين.',
                'جرّبت منصّة أجنبية ولم تستطع التعامل مع الدفع عند الاستلام كما يجب.',
                'تحتاج المتجر بالكردية والعربية، لا بالإنجليزية فقط.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'دفع عند الاستلام مُعالَج كما يجب — تأكيد وتسليم للمندوب وتسوية حسابات، لا مجرد ملاحظة على الطلب.',
                'وسائل دفع محلية مربوطة، ويُؤكَّد الربط معك قبل أن نَعِد به علناً.',
                'ربط مع شركة التوصيل فتخرج الطلبات كإرساليات لا كرسائل واتساب.',
                'مخزون مشترك مع محلك إن وُجد، فلا يختلف الأونلاين عن الرف أبداً.',
                'كردي وعربي وإنجليزي، وصفحات منتجات مصمّمة لليمين إلى اليسار كما ينبغي.',
                'لوحة طلبات يديرها موظفوك دون الرجوع إليك.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'نفهم كيف تبيع الآن',
                    'body' => 'كيف تصل الطلبات، وكيف تُدفَع، وكيف تُسلَّم. المتجر يُبنى حول ذلك.',
                ],
                [
                    'step' => 'نطاق وسعر ثابتان',
                    'body' => 'نطاق مكتوب وسعر وتاريخ، محدَّد فيه أي وسائل دفع وتوصيل مشمولة.',
                ],
                [
                    'step' => 'البناء وتحميل الكتالوج',
                    'body' => 'نبني المتجر وندخل منتجاتك وصورك وأسعارك.',
                ],
                [
                    'step' => 'اختبار بطلبات حقيقية',
                    'body' => 'ننفّذ طلبات حقيقية من البداية للنهاية — دفعاً وتوصيلاً — قبل أن تعلن عنه.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'هل تدعمون الدفع عند الاستلام؟',
                    'a' => 'نعم، وبشكل صحيح: تأكيد الطلب، وتسليم المندوب، وتسوية ما حصّله المندوب مقابل ما يخصّك. معظم المنصّات تعامله كإضافة ثانوية.',
                ],
                [
                    'q' => 'ما وسائل الدفع الإلكتروني التي تستطيعون ربطها؟',
                    'a' => 'أخبرنا بمزوّدي الدفع الذين لديك حساب تاجر معهم وسنؤكّد بالضبط ما نستطيع ربطه قبل أن نَعِد بشيء. لا ندرج تكاملاً لم نبنِه.',
                ],
                [
                    'q' => 'هل يمكن استخدام شركة التوصيل التي أتعامل معها؟',
                    'a' => 'غالباً نعم — يعتمد على توفّر واجهة ربط لديهم. أخبرنا بمن تتعامل معه وسنتحقّق قبل أن تلتزم.',
                ],
                [
                    'q' => 'هل ستُربك الطلبات الأونلاين مخزون محلي؟',
                    'a' => 'لا، إذا ربطنا الاثنين. وإن كان لديك نظام نقاط بيع، فالأونلاين والمحل يتشاركان جرداً واحداً.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'فرۆشگای ئۆنڵاین',
            'title' => 'فرۆشگای ئۆنڵاین بۆ عێراق — پارەدان و گەیاندنی ناوخۆیی | GoSoftware',
            'meta' => 'لە عێراق ئۆنڵاین بفرۆشە بەو شێوازانەی پارەدان و گەیاندن کە کڕیارەکانت بەڕاستی بەکاریان دەهێنن: پارەدان لە کاتی وەرگرتن، جزدانی ناوخۆیی و گەیێنەری ناوخۆیی.',
            'h1' => 'لە عێراق ئۆنڵاین بفرۆشە — بەو شێوازانەی پارەدان و گەیاندن کە کڕیارەکانت بەڕاستی بەکاریان دەهێنن',
            'card' => 'فرۆشگایەکی ئۆنڵاین کە بۆ ئەم بازاڕە دروستکراوە: پارەدان لە کاتی وەرگرتن، جزدانی ناوخۆیی، گەیێنەری ناوخۆیی، بە کوردی و عەرەبی.',
            'intro' => 'پلاتفۆڕمی بیانی وا دادەنێت کە کارتی بانکی و ناونیشانی پۆستەت هەیە. هیچ کامیان ئەو شێوازە نین کە زۆربەی داواکارییەکان لە عێراقدا پێی پارەیان بۆ دەدرێت و دەگەیەنرێن. فرۆشگای ئۆنڵاین لێرە تەنها کاتێک سەرکەوتوو دەبێت کە پارەدان لە کاتی وەرگرتن و ئەو جزدانانەی خەڵک بەڕاستی هەیانە و ئەو گەیێنەرانەی بەڕاستی دێن پشتگیری بکات.',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'لە ڕێگەی نامەکانی ئینستاگرامەوە دەفرۆشیت و ناتوانیت لەگەڵ داواکارییەکاندا بگونجێیت.',
                'دوکانێکت هەیە و دەتەوێت هەمان کۆگا ئۆنڵاینیش بفرۆشرێت بەبێ ئەوەی دوو جار بیژمێریت.',
                'پلاتفۆڕمێکی بیانیت تاقی کردەوە و نەیتوانی پارەدان لە کاتی وەرگرتن بەدروستی جێبەجێ بکات.',
                'فرۆشگاکەت بە کوردی و عەرەبی پێویستە، نەک تەنها بە ئینگلیزی.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'پارەدان لە کاتی وەرگرتن بەدروستی جێبەجێکراو — پشتڕاستکردنەوە و ڕادەستکردن بە گەیێنەر و لێکدانەوەی حساب، نەک تەنها تێبینییەک لەسەر داواکارییەکە.',
                'شێوازی پارەدانی ناوخۆیی پەیوەستکراو، و پەیوەندییەکە لەگەڵت پشتڕاست دەکرێتەوە پێش ئەوەی بە ئاشکرا بەڵێنی پێبدەین.',
                'پەیوەندی بە کۆمپانیای گەیاندنەوە تاکو داواکارییەکان وەک نێردراو دەربچن نەک وەک نامەی واتسئەپ.',
                'کۆگای هاوبەش لەگەڵ دوکانەکەت ئەگەر هەتبێت، تاکو ئۆنڵاین و ناو دوکان هەرگیز ناکۆک نەبن.',
                'کوردی و عەرەبی و ئینگلیزی، بە لاپەڕەی بەرهەمی ڕاست بۆ چەپی بەدروستی دیزاینکراو.',
                'داشبۆردی داواکارییەکان کە کارمەندەکانت بەبێ تۆ بەڕێوەی دەبەن.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'دەزانین ئێستا چۆن دەفرۆشیت',
                    'body' => 'داواکارییەکان چۆن دێن، چۆن پارەیان بۆ دەدرێت، چۆن دەگەیەنرێن. فرۆشگاکە لە دەوری ئەوە دروست دەکرێت.',
                ],
                [
                    'step' => 'پێکهاتە و نرخی جێگیر',
                    'body' => 'پێکهاتەی نووسراو و نرخ و بەروار، لەگەڵ دیاریکردنی ئەوەی کام پەیوەندی پارەدان و گەیاندن تێدایە.',
                ],
                [
                    'step' => 'دروستکردن و بارکردنی کاتالۆگ',
                    'body' => 'فرۆشگاکە دروست دەکەین و بەرهەم و وێنە و نرخەکانت دەخەینە ناوی.',
                ],
                [
                    'step' => 'تاقیکردنەوە بە داواکاری ڕاستەقینە',
                    'body' => 'داواکاری ڕاستەقینە لە سەرەتاوە تا کۆتایی جێبەجێ دەکەین — پارەدان و گەیاندن — پێش ئەوەی ڕایبگەیەنیت.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'پارەدان لە کاتی وەرگرتن جێبەجێ دەکەن؟',
                    'a' => 'بەڵێ، و بەدروستی: پشتڕاستکردنەوەی داواکاری، ڕادەستکردن بە گەیێنەر، و لێکدانەوەی ئەوەی گەیێنەرەکە وەریگرتووە بەرامبەر بەوەی بۆ تۆیە. زۆربەی پلاتفۆڕمەکان وەک شتێکی لاوەکی سەیری دەکەن.',
                ],
                [
                    'q' => 'کام شێوازی پارەدانی ئۆنڵاین دەتوانن پەیوەست بکەن؟',
                    'a' => 'پێمان بڵێ لەگەڵ کام دابینکەر هەژماری بازرگانیت هەیە و بەوردی پشتڕاست دەکەینەوە چیمان دەکرێت پەیوەست بکەین پێش ئەوەی بەڵێنی شتێک بدەین. ئێمە ئەو پەیوەندییە لیست ناکەین کە دروستمان نەکردووە.',
                ],
                [
                    'q' => 'دەتوانرێت کۆمپانیای گەیاندنی ئاسایی خۆم بەکاربهێنم؟',
                    'a' => 'زۆرجار بەڵێ — بەندە بەوەی ئایا ڕێگەی پەیوەندییان هەیە. پێمان بڵێ لەگەڵ کێ کار دەکەیت و پێش پابەندبوونت دەیپشکنین.',
                ],
                [
                    'q' => 'داواکاری ئۆنڵاین کۆگای دوکانەکەم تێکدەدات؟',
                    'a' => 'نەخێر، ئەگەر هەردووکیان پەیوەست بکەین. ئەگەر سیستەمی فرۆشتنیشت هەبێت، ئۆنڵاین و ناو دوکان یەک ژماردنی کۆگا هاوبەش دەکەن.',
                ],
            ],
        ],
    ],

    'support-and-maintenance' => [
        'tag' => 'SUPPORT',
        'whatsapp' => 'service_support',
        'icon' => 'wrench',
        'en' => [
            'name' => 'Support & Maintenance',
            'title' => 'Website & System Maintenance in Erbil | GoSoftware',
            'meta' => 'Already have a website or system? We will keep it running — including ones another company built. Support and maintenance from Erbil.',
            'h1' => 'Already have a system? We\'ll keep it running',
            'card' => 'Including the ones somebody else built and stopped answering about.',
            'intro' => 'The most common call we get is not "build me something new". It is "the company that built this has stopped replying". Taking over someone else\'s system is ordinary work for us, and we will tell you honestly whether it is worth maintaining or replacing.',
            'whoTitle' => 'Who this is for',
            'who' => [
                'The developer who built your site or system has gone quiet.',
                'Something breaks and there is nobody to call.',
                'Your site is running on software that has not been updated in years.',
                'You need someone to answer for uptime, backups and security — in writing.',
            ],
            'getTitle' => 'What you get',
            'get' => [
                'A written health check first: what you have, what state it is in, and what it would take to look after.',
                'An honest verdict on maintain-versus-rebuild, even when rebuild is not the answer we would profit more from.',
                'Bug fixes, updates and security patches on an agreed response time.',
                'Backups that are actually tested, not just configured.',
                'A named person who answers, in Kurdish, Arabic or English.',
                'Your code and credentials stay yours throughout.',
            ],
            'processTitle' => 'How it runs',
            'process' => [
                [
                    'step' => 'Send us what exists',
                    'body' => 'Access, or just a URL. We look before we quote.',
                ],
                [
                    'step' => 'You get a health check',
                    'body' => 'A written summary of what is there, what is risky, and what we would do first.',
                ],
                [
                    'step' => 'Agree a support level',
                    'body' => 'Response times and what is covered, in writing, before anything starts.',
                ],
                [
                    'step' => 'We take it on',
                    'body' => 'We take over hosting and accounts if you want, or work alongside your existing setup.',
                ],
            ],
            'faqTitle' => 'Questions people ask before they commit',
            'faqs' => [
                [
                    'q' => 'Can you take over a system another company built?',
                    'a' => 'Yes. It is one of the most common things we are asked to do. Send us access or a link and we will assess it before quoting.',
                ],
                [
                    'q' => 'What if the previous developer will not hand over the code?',
                    'a' => 'Tell us the situation. Often more is recoverable than people expect, and we will tell you plainly if it is not.',
                ],
                [
                    'q' => 'How fast do you respond when something breaks?',
                    'a' => 'That is written into the support agreement rather than left vague. The exact response times are being finalised — ask and we will tell you what applies today.',
                ],
                [
                    'q' => 'Do I have to move my hosting to you?',
                    'a' => 'No. We can work with your current hosting, or take it over if you would rather have one party responsible.',
                ],
            ],
        ],
        'ar' => [
            'name' => 'الدعم والصيانة',
            'title' => 'صيانة المواقع والأنظمة في أربيل | GoSoftware',
            'meta' => 'لديك موقع أو نظام بالفعل؟ سنُبقيه يعمل — بما في ذلك ما بنته شركة أخرى. دعم وصيانة من أربيل.',
            'h1' => 'لديك نظام بالفعل؟ سنُبقيه يعمل',
            'card' => 'بما في ذلك ما بناه شخص آخر ثم توقّف عن الرد.',
            'intro' => 'أكثر اتصال يصلنا ليس «ابنوا لي شيئاً جديداً»، بل «الشركة التي بنت هذا توقّفت عن الرد». استلام نظام بناه غيرنا عمل اعتيادي لدينا، وسنخبرك بصراحة هل يستحق الصيانة أم الاستبدال.',
            'whoTitle' => 'لمن هذه الخدمة',
            'who' => [
                'المطوّر الذي بنى موقعك أو نظامك اختفى.',
                'يتعطّل شيء ولا يوجد من تتصل به.',
                'موقعك يعمل على برمجيات لم تُحدَّث منذ سنوات.',
                'تحتاج من يتحمّل مسؤولية التشغيل والنسخ الاحتياطي والأمان — كتابةً.',
            ],
            'getTitle' => 'ماذا تحصل عليه',
            'get' => [
                'فحص مكتوب أولاً: ماذا لديك، وفي أي حال هو، وما الذي تتطلّبه رعايته.',
                'رأي صريح بين الصيانة وإعادة البناء، حتى حين لا تكون إعادة البناء الجواب الأربح لنا.',
                'إصلاح أعطال وتحديثات وترقيعات أمنية ضمن زمن استجابة متّفق عليه.',
                'نسخ احتياطي مُختبَر فعلاً، لا مُعَدّ فقط.',
                'شخص محدّد بالاسم يردّ عليك، بالكردية أو العربية أو الإنجليزية.',
                'شيفرتك وبيانات دخولك تبقى ملكك طوال الوقت.',
            ],
            'processTitle' => 'كيف يسير العمل',
            'process' => [
                [
                    'step' => 'أرسل لنا ما هو موجود',
                    'body' => 'صلاحية دخول، أو حتى مجرد رابط. ننظر قبل أن نسعّر.',
                ],
                [
                    'step' => 'تحصل على فحص للحالة',
                    'body' => 'ملخّص مكتوب لما هو موجود، وما هو خطِر، وما الذي سنفعله أولاً.',
                ],
                [
                    'step' => 'نتفق على مستوى دعم',
                    'body' => 'أزمنة الاستجابة وما هو مشمول، كتابةً، قبل أن يبدأ أي شيء.',
                ],
                [
                    'step' => 'نستلمه',
                    'body' => 'نستلم الاستضافة والحسابات إن أردت، أو نعمل إلى جانب إعدادك الحالي.',
                ],
            ],
            'faqTitle' => 'أسئلة يطرحها الناس قبل أن يقرّروا',
            'faqs' => [
                [
                    'q' => 'هل تستطيعون استلام نظام بنته شركة أخرى؟',
                    'a' => 'نعم. وهو من أكثر ما يُطلَب منا. أرسل لنا صلاحية أو رابطاً وسنقيّمه قبل التسعير.',
                ],
                [
                    'q' => 'ماذا لو رفض المطوّر السابق تسليم الشيفرة؟',
                    'a' => 'أخبرنا بالوضع. غالباً ما يمكن استرجاع أكثر مما يتوقّع الناس، وسنقول لك بوضوح إن لم يكن ذلك ممكناً.',
                ],
                [
                    'q' => 'كم تستغرقون في الاستجابة عند العطل؟',
                    'a' => 'هذا مكتوب في اتفاقية الدعم لا متروك للغموض. أزمنة الاستجابة الدقيقة قيد الإقرار — اسأل وسنخبرك بما هو مطبَّق اليوم.',
                ],
                [
                    'q' => 'هل يجب أن أنقل استضافتي إليكم؟',
                    'a' => 'لا. نستطيع العمل مع استضافتك الحالية، أو استلامها إن فضّلت أن تكون المسؤولية على جهة واحدة.',
                ],
            ],
        ],
        'ckb' => [
            'name' => 'پشتگیری و چاودێری',
            'title' => 'چاودێری ماڵپەڕ و سیستەم لە هەولێر | GoSoftware',
            'meta' => 'پێشتر ماڵپەڕ یان سیستەمت هەیە؟ بەکاری دەهێڵینەوە — لەوانەش ئەوانەی کۆمپانیایەکی تر دروستی کردوون. پشتگیری و چاودێری لە هەولێرەوە.',
            'h1' => 'پێشتر سیستەمت هەیە؟ بەکاری دەهێڵینەوە',
            'card' => 'لەوانەش ئەوانەی کەسێکی تر دروستی کردوون و پاشان وەڵامی نەداوەتەوە.',
            'intro' => 'باوترین پەیوەندی کە پێمان دەگات ئەوە نییە «شتێکی نوێم بۆ دروست بکەن». ئەوەیە «ئەو کۆمپانیایەی ئەمەی دروستکرد چیتر وەڵام ناداتەوە». وەرگرتنی سیستەمی کەسانی تر بۆ ئێمە کارێکی ئاسایییە، و بە ڕاشکاوی پێت دەڵێین ئایا شایەنی چاودێرییە یان گۆڕینەوە.',
            'whoTitle' => 'ئەمە بۆ کێیە',
            'who' => [
                'ئەو گەشەپێدەرەی ماڵپەڕ یان سیستەمەکەی دروستکردیت ون بووە.',
                'شتێک تێکدەچێت و کەس نییە پەیوەندی پێوە بکەیت.',
                'ماڵپەڕەکەت لەسەر سۆفتوێرێک کار دەکات کە ساڵانێکە نوێ نەکراوەتەوە.',
                'پێویستت بە کەسێکە بەرپرسیارێتی کارکردن و پاڵپشت و پاراستن هەڵبگرێت — بە نووسراوی.',
            ],
            'getTitle' => 'چی وەردەگریت',
            'get' => [
                'یەکەم جار پشکنینێکی نووسراو: چیت هەیە، لە چ دۆخێکدایە، و چاودێریکردنی چی دەخوازێت.',
                'ڕایەکی ڕاشکاو لە نێوان چاودێری و دووبارە دروستکردنەوە، تەنانەت کاتێک دووبارە دروستکردنەوە ئەو وەڵامە نییە کە قازانجی زیاترمان لێدەکەین.',
                'چاککردنی هەڵە و نوێکردنەوە و پاڵپشتی پاراستن لە ماوەی وەڵامدانەوەیەکی ڕێککەوتوودا.',
                'پاڵپشتی کە بەڕاستی تاقی کراوەتەوە، نەک تەنها ڕێکخراوە.',
                'کەسێکی دیاریکراو بە ناو کە وەڵامت دەداتەوە، بە کوردی یان عەرەبی یان ئینگلیزی.',
                'کۆد و زانیاری چوونەژوورەوەت بە درێژایی کات هی خۆت دەمێننەوە.',
            ],
            'processTitle' => 'چۆن دەڕوات',
            'process' => [
                [
                    'step' => 'ئەوەی هەیە بۆمان بنێرە',
                    'body' => 'ڕێگەپێدانی چوونەژوورەوە، یان تەنها لینکێک. پێش نرخدان سەیری دەکەین.',
                ],
                [
                    'step' => 'پشکنینی دۆخ وەردەگریت',
                    'body' => 'کورتەیەکی نووسراو لەوەی چی هەیە، چی مەترسیدارە، و یەکەم چی دەکەین.',
                ],
                [
                    'step' => 'لەسەر ئاستی پشتگیری ڕێک دەکەوین',
                    'body' => 'ماوەی وەڵامدانەوە و ئەوەی دەگرێتەوە، بە نووسراوی، پێش دەستپێکردنی هیچ شتێک.',
                ],
                [
                    'step' => 'وەریدەگرین',
                    'body' => 'هۆستینگ و هەژمارەکان وەردەگرین ئەگەر بتەوێت، یان لەتەنیشت ڕێکخستنی ئێستات کار دەکەین.',
                ],
            ],
            'faqTitle' => 'ئەو پرسیارانەی خەڵک پێش بڕیاردان دەیکەن',
            'faqs' => [
                [
                    'q' => 'دەتوانن سیستەمێک وەربگرن کە کۆمپانیایەکی تر دروستی کردووە؟',
                    'a' => 'بەڵێ. ئەمە یەکێکە لە باوترین شتەکان کە داوامان لێدەکرێت. ڕێگەپێدان یان لینکێکمان بۆ بنێرە و پێش نرخدان هەڵیدەسەنگێنین.',
                ],
                [
                    'q' => 'ئەی ئەگەر گەشەپێدەری پێشوو کۆدەکە ڕادەست نەکات؟',
                    'a' => 'دۆخەکەمان پێبڵێ. زۆرجار زیاتر لەوەی خەڵک چاوەڕێی دەکات دەگەڕێتەوە، و ئەگەر نەکرا بە ڕوونی پێت دەڵێین.',
                ],
                [
                    'q' => 'کاتێک شتێک تێکدەچێت چەند بە خێرایی وەڵام دەدەنەوە؟',
                    'a' => 'ئەمە لە ڕێککەوتنی پشتگیریدا نووسراوە نەک بە ناڕوونی جێهێڵدراوە. ماوە وردەکانی وەڵامدانەوە لە قۆناغی کۆتاییدان — بپرسە و پێت دەڵێین ئەمڕۆ چی جێبەجێ دەکرێت.',
                ],
                [
                    'q' => 'دەبێت هۆستینگەکەم بگوازمەوە بۆ لای ئێوە؟',
                    'a' => 'نەخێر. دەتوانین لەگەڵ هۆستینگی ئێستات کار بکەین، یان وەریبگرین ئەگەر پێت باشترە بەرپرسیارێتی لەلایەن یەک لایەنەوە بێت.',
                ],
            ],
        ],
    ],
];
