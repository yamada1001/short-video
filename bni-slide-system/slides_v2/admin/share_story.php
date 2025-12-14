<?php
require_once __DIR__ . '/../config.php';
// シェアストーリー管理画面 - 簡易版
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>シェアストーリー管理 | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-book-open"></i> シェアストーリー管理</h1>
            </div>
            <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-home"></i> 管理画面トップへ
            </a>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="actions-bar">
                <div></div>
                <button class="btn btn-success" onclick="openSlide()"><i class="fas fa-play"></i> スライド表示</button>
            </div>
            <form id="storyForm">
                <div class="form-group">
                    <label>メンバー選択</label>
                    <select id="memberId" required><option value="">選択してください</option></select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 保存</button>
                    <button type="button" class="btn btn-danger" onclick="deleteData()"><i class="fas fa-trash"></i> 削除</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const API = '../api/share_story_crud.php';
        const MEMBERS_API = '../api/members_crud.php';
        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            document.getElementById('storyForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData();
                formData.append('action', 'save');
                formData.append('member_id', document.getElementById('memberId').value);
                const res = await fetch(API, { method: 'POST', body: formData });
                const data = await res.json();
                alert(data.success ? '保存しました' : 'エラー: ' + (data.error || '不明'));
            });
        });
        async function loadMembers() {
            const res = await fetch(MEMBERS_API + '?action=list');
            const data = await res.json();
            if (data.success) {
                document.getElementById('memberId').innerHTML = '<option value="">選択してください</option>' + 
                    data.members.filter(m => m.is_active == 1).map(m => `<option value="${m.id}">${m.name}</option>`).join('');
                loadData();
            }
        }
        async function loadData() {
            const res = await fetch(`${API}?action=get_latest`);
            const data = await res.json();
            if (data.success && data.data) document.getElementById('memberId').value = data.data.member_id || '';
        }
        async function deleteData() {
            if (!confirm('削除しますか？')) return;
            const res = await fetch(API, {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete' })
            });
            const data = await res.json();
            if (data.success) { alert('削除しました'); loadData(); }
        }
        function openSlide() {
            if (!document.getElementById('memberId').value) {
                alert('メンバーを選択してください');
                return;
            }
            window.open(`../slides/share_story.php`, '_blank');
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; }
        .header h1 { font-size: 24px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px; }
        .actions-bar { display: flex; justify-content: space-between; margin-bottom: 25px; }
        .date-selector { display: flex; align-items: center; gap: 15px; }
        .date-selector input { padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; display: inline-flex; gap: 8px; }
        .btn-primary { background: #C8102E; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</body>
</html>
