/**
 * 京都トップページ専用JavaScript
 * 全日程の達成率を合算して表示
 */

document.addEventListener('DOMContentLoaded', () => {
    // デバッグ: 全てのlocalStorageキーを表示
    console.log('=== localStorage Debug ===');
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key.includes('checked_spots')) {
            const value = localStorage.getItem(key);
            console.log('Key:', key);
            console.log('Value:', value);
        }
    }
    console.log('=========================');

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
        console.log(`Checking key: ${key}`);
        console.log(`Data found:`, savedData);

        if (savedData) {
            try {
                const checkedIds = JSON.parse(savedData);
                console.log(`Checked IDs (${checkedIds.length}):`, checkedIds);
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
        console.log(`Updated .checked-count to: ${totalChecked}`);
    } else {
        console.error('.checked-count element not found!');
    }

    console.log('京都旅行 達成率:', totalChecked, '/ 19');
});
