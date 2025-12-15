<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>リファーラル真正度 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 24px; font-weight: 600; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .actions-bar { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background: #C8102E; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; }
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .save-btn { width: 100%; padding: 12px; background: #C8102E; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .list { margin-top: 30px; }
        .list-item { background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .btn-danger { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-check-circle"></i> リファーラル真正度</h1>
            </div>
            <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-home"></i> 管理画面トップへ
            </a>
        </div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-secondary" onclick="location.href='index.php'"><i class="fas fa-arrow-left"></i> 戻る</button>
            <button class="btn btn-secondary" onclick="viewSlide()">
                <i class="fas fa-external-link-alt"></i> スライドを確認（p.227）
            </button>
        </div>

        <div class="card">
            <h2 style="color: #C8102E; margin-bottom: 20px;">リファーラル検証を追加</h2>
            <form id="addForm">
                <div class="form-group">
                    <label>リファーラルを出した人</label>
                    <select id="fromMember" required></select>
                </div>
                <div class="form-group">
                    <label>リファーラルを受け取った人</label>
                    <select id="toMember" required></select>
                </div>
                <button type="submit" class="save-btn"><i class="fas fa-save"></i> 保存</button>
            </form>
        </div>

        <div class="list" id="verificationList"></div>
    </div>

    <script>
        let members = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadMembers();
            loadVerifications();

            document.getElementById('addForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const fromMemberId = document.getElementById('fromMember').value;
                const toMemberId = document.getElementById('toMember').value;

                if (fromMemberId === toMemberId) {
                    alert('同じメンバーは選択できません');
                    return;
                }

                try {
                    const response = await fetch('../api/referral_check_crud.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'create',
                            week_date: new Date().toISOString().split('T')[0],
                            from_member_id: fromMemberId,
                            to_member_id: toMemberId
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        alert('追加しました！');
                        loadVerifications();
                    } else {
                        alert('エラー: ' + data.error);
                    }
                } catch (error) {
                    alert('通信エラー: ' + error);
                }
            });
        });

        async function loadMembers() {
            try {
                const response = await fetch('../api/members_crud.php?action=list');
                const data = await response.json();
                if (data.success) {
                    members = data.members.filter(m => m.is_active == 1);
                    const fromSelect = document.getElementById('fromMember');
                    const toSelect = document.getElementById('toMember');
                    
                    members.forEach(m => {
                        fromSelect.innerHTML += `<option value="${m.id}">${m.name}</option>`;
                        toSelect.innerHTML += `<option value="${m.id}">${m.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('メンバー取得エラー:', error);
            }
        }

        async function loadVerifications() {
            try {
                const response = await fetch('../api/referral_check_crud.php?action=get_latest');
                const data = await response.json();
                if (data.success) {
                    displayVerifications(data.verifications);

                    // 最新データをフォームに反映
                    if (data.verifications && data.verifications.length > 0) {
                        const latest = data.verifications[0];
                        document.getElementById('fromMember').value = latest.from_member_id;
                        document.getElementById('toMember').value = latest.to_member_id;
                    }
                }
            } catch (error) {
                console.error('データ取得エラー:', error);
            }
        }

        function displayVerifications(verifications) {
            const container = document.getElementById('verificationList');
            if (verifications.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">データがありません</p>';
                return;
            }

            container.innerHTML = verifications.map(v => `
                <div class="list-item">
                    <span><strong>${v.from_name}</strong> → <strong>${v.to_name}</strong></span>
                    <button class="btn btn-danger" onclick="deleteVerification(${v.id})"><i class="fas fa-trash"></i> 削除</button>
                </div>
            `).join('');
        }

        async function deleteVerification(id) {
            if (!confirm('削除してもよろしいですか？')) return;

            try {
                const response = await fetch('../api/referral_check_crud.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id: id })
                });

                const data = await response.json();
                if (data.success) {
                    alert('削除しました');
                    loadVerifications();
                } else {
                    alert('エラー: ' + data.error);
                }
            } catch (error) {
                alert('通信エラー: ' + error);
            }
        }

        // スライドを確認
        function viewSlide() {
            const pageNumber = 227;
            window.open(`../index.php#${pageNumber}`, '_blank', 'width=1920,height=1080');
        }
    </script>
</body>
</html>
