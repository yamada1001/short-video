<?php
/**
 * 買取サービス専用データ
 * くるま買取ケイヴィレッジ
 */

// 買取の流れ
$kaitori_process = [
    [
        'step' => '01',
        'title' => '無料査定お申込み',
        'description' => 'お電話またはWebフォームから無料査定をお申込みください。',
        'icon' => 'fa-solid fa-phone'
    ],
    [
        'step' => '02',
        'title' => '査定日時の調整',
        'description' => 'ご都合の良い日時を調整させていただきます。出張査定も対応可能です。',
        'icon' => 'fa-solid fa-calendar-check'
    ],
    [
        'step' => '03',
        'title' => '査定実施',
        'description' => '経験豊富なスタッフがお車の状態を丁寧に査定いたします。',
        'icon' => 'fa-solid fa-clipboard-check'
    ],
    [
        'step' => '04',
        'title' => '査定額のご提示',
        'description' => '査定結果をその場でご提示。ご納得いただければ次のステップへ。',
        'icon' => 'fa-solid fa-sack-dollar'
    ],
    [
        'step' => '05',
        'title' => 'ご契約・お支払い',
        'description' => '必要書類を確認後、契約手続き。即日現金買取も可能です。',
        'icon' => 'fa-solid fa-file-signature'
    ]
];

// 高価買取のポイント
$kaitori_strengths = [
    [
        'title' => '他社より高価買取',
        'description' => '独自の販売ルートを持つため、他社よりも高い買取価格を実現しています。',
        'icon' => 'fa-solid fa-arrow-trend-up',
        'color' => '#2563eb'
    ],
    [
        'title' => '出張査定無料',
        'description' => 'お忙しい方、遠方の方も安心。ご指定の場所まで無料で出張査定いたします。',
        'icon' => 'fa-solid fa-truck',
        'color' => '#10b981'
    ],
    [
        'title' => '即日現金買取',
        'description' => '査定額にご納得いただければ、その場で現金買取も可能です。',
        'icon' => 'fa-solid fa-money-bill-wave',
        'color' => '#f59e0b'
    ],
    [
        'title' => '手続き簡単',
        'description' => '面倒な手続きはすべて当店にお任せ。必要書類のご案内から代行まで対応します。',
        'icon' => 'fa-solid fa-check-circle',
        'color' => '#8b5cf6'
    ]
];

// よくある質問
$kaitori_faq = [
    [
        'question' => '査定は本当に無料ですか？',
        'answer' => 'はい、完全無料です。出張査定も無料で対応いたします。査定後にキャンセルされても費用は一切かかりません。'
    ],
    [
        'question' => 'どんな車でも買い取ってもらえますか？',
        'answer' => '国産車・輸入車問わず、ほとんどの車両を買取対象としています。事故車や不動車でもまずはご相談ください。'
    ],
    [
        'question' => '買取に必要な書類は何ですか？',
        'answer' => '車検証、自賠責保険証、印鑑証明書、実印などが必要です。詳しくは査定時にご説明いたします。'
    ],
    [
        'question' => '査定から買取までどのくらいかかりますか？',
        'answer' => '最短で即日対応可能です。お急ぎの場合はご相談ください。'
    ],
    [
        'question' => 'ローンが残っていても売却できますか？',
        'answer' => 'はい、可能です。ローン残債の処理方法についてもご相談に乗りますのでお気軽にお問い合わせください。'
    ]
];

// 買取実績（サンプル）
$kaitori_results = [
    [
        'maker' => 'トヨタ',
        'model' => 'プリウス',
        'year' => '2018年',
        'mileage' => '5万km',
        'price' => '120万円'
    ],
    [
        'maker' => 'ホンダ',
        'model' => 'N-BOX',
        'year' => '2020年',
        'mileage' => '3万km',
        'price' => '95万円'
    ],
    [
        'maker' => '日産',
        'model' => 'セレナ',
        'year' => '2017年',
        'mileage' => '6万km',
        'price' => '110万円'
    ]
];
