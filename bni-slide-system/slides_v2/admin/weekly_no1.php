<?php
require_once __DIR__ . '/../config.php';
// 週間No.1管理画面 - 簡易版
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>週間No.1管理 | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./css/admin_common.css">
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-trophy"></i> 週間No.1管理</h1>
                <div class="subtitle">BNI Slide System V2 - Weekly No.1 Management</div>
            </div>
            <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-home"></i> 管理画面トップへ
            </a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>週間No.1情報管理</h2>
            </div>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> 週間No.1について</h4>
                <ul>
                    <li>p.28: 外部リファーラル1位、ビジター招待1位、1to1 1位を表示</li>
                    <li>メンバー選択と件数を入力してください</li>
                </ul>
            </div>

            <div class="actions-bar">
                <div></div>
                <button class="btn btn-success" onclick="openSlide()">
                    <i class="fas fa-play"></i> スライド表示
                </button>
            </div>

            <form id="no1Form">
                <div class="form-row">
                    <div class="form-group">
                        <label>外部リファーラル1位</label>
                        <select id="externalReferralMemberId">
                            <option value="">選択してください</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>件数</label>
                        <input type="number" id="externalReferralCount" min="0" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>ビジター招待1位</label>
                        <select id="visitorInvitationMemberId">
                            <option value="">選択してください</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>件数</label>
                        <input type="number" id="visitorInvitationCount" min="0" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>1to1 1位</label>
                        <select id="oneToOneMemberId">
                            <option value="">選択してください</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>件数</label>
                        <input type="number" id="oneToOneCount" min="0" value="0">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 保存
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteData()">
                        <i class="fas fa-trash"></i> 削除
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = '../api/weekly_no1_crud.php';
        const MEMBERS_API = '../api/members_crud.php';
        let members = [];

        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            document.getElementById('no1Form').addEventListener('submit', handleSubmit);
        });

        async function loadMembers() {
            const response = await fetch(MEMBERS_API + '?action=list');
            const data = await response.json();
            if (data.success) {
                members = data.members.filter(m => m.is_active == 1);
                renderMemberOptions();
                loadData();
            }
        }

        function renderMemberOptions() {
            const selects = ['externalReferralMemberId', 'visitorInvitationMemberId', 'oneToOneMemberId'];
            const options = '<option value="">選択してください</option>' + 
                members.map(m => `<option value="${m.id}">${m.name}</option>`).join('');
            selects.forEach(id => document.getElementById(id).innerHTML = options);
        }

        async function loadData() {
            const response = await fetch(`${API_BASE}?action=get_latest`);
            const data = await response.json();
            if (data.success && data.data) {
                document.getElementById('externalReferralMemberId').value = data.data.external_referral_member_id || '';
                document.getElementById('externalReferralCount').value = data.data.external_referral_count || 0;
                document.getElementById('visitorInvitationMemberId').value = data.data.visitor_invitation_member_id || '';
                document.getElementById('visitorInvitationCount').value = data.data.visitor_invitation_count || 0;
                document.getElementById('oneToOneMemberId').value = data.data.one_to_one_member_id || '';
                document.getElementById('oneToOneCount').value = data.data.one_to_one_count || 0;
            }
        }

        async function handleSubmit(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'save');
            formData.append('external_referral_member_id', document.getElementById('externalReferralMemberId').value);
            formData.append('external_referral_count', document.getElementById('externalReferralCount').value);
            formData.append('visitor_invitation_member_id', document.getElementById('visitorInvitationMemberId').value);
            formData.append('visitor_invitation_count', document.getElementById('visitorInvitationCount').value);
            formData.append('one_to_one_member_id', document.getElementById('oneToOneMemberId').value);
            formData.append('one_to_one_count', document.getElementById('oneToOneCount').value);

            const response = await fetch(API_BASE, { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                alert('保存しました');
            } else {
                alert('エラー: ' + (data.error || '不明なエラー'));
            }
        }

        async function deleteData() {
            if (!confirm('削除してもよろしいですか？')) return;
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete' })
            });
            const data = await response.json();
            if (data.success) {
                alert('削除しました');
                loadData();
            }
        }

        function openSlide() {
            window.open(`../slides/weekly_no1.php`, '_blank');
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 24px; font-weight: 600; }
        .header .subtitle { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px; }
        .card-header { margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #C8102E; }
        .card-header h2 { color: #C8102E; font-size: 20px; }
        .actions-bar { display: flex; justify-content: space-between; margin-bottom: 25px; gap: 15px; }
        .date-selector { display: flex; align-items: center; gap: 15px; }
        .date-selector label { font-weight: 500; }
        .date-selector input[type="date"] { padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: #C8102E; color: white; }
        .btn-primary:hover { background: #a00a24; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .info-box { background: #f0f8ff; border-left: 4px solid #0066cc; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        .info-box h4 { color: #0066cc; font-size: 14px; margin-bottom: 8px; }
        .info-box ul { margin-top: 8px; margin-left: 20px; font-size: 13px; }
        .form-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-actions { display: flex; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</body>
</html>
