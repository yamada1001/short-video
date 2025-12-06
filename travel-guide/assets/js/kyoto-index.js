/**
 * 京都トップページ専用JavaScript
 * 全日程の達成率を合算して表示
 */

document.addEventListener('DOMContentLoaded', () => {
    // 全日程のlocalStorageキーを定義
    const dayKeys = [
        'checked_spots_/travel-guide/kyoto/days/day1.php',
        'checked_spots_/travel-guide/kyoto/days/day2.php',
        'checked_spots_/travel-guide/kyoto/days/day3.php'
    ];

    // 各日程のチェック数を取得
    let totalChecked = 0;

    dayKeys.forEach(key => {
        const savedData = localStorage.getItem(key);
        if (savedData) {
            try {
                const checkedIds = JSON.parse(savedData);
                totalChecked += checkedIds.length;
            } catch (e) {
                console.error('localStorage parse error:', e);
            }
        }
    });

    // 統計情報を更新
    const checkedElement = document.querySelector('.checked-count');
    if (checkedElement) {
        checkedElement.textContent = totalChecked;
    }

    console.log('京都旅行 達成率:', totalChecked, '/ 19');
});
