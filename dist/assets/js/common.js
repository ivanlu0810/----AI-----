// 建立訓練動作卡片
function createExerciseCard(exercise, index) {
    const card = document.createElement('div');
    card.className = 'equipment-card';

    // 計算建議重量範圍
    const hypertrophyWeightMin = Math.round(parseFloat(exercise.hypertrophy_load_min_pct) || 0);
    const hypertrophyWeightMax = hypertrophyWeightMin + 15;
    const fatlossWeightMin = hypertrophyWeightMin - 20;
    const fatlossWeightMax = hypertrophyWeightMin - 5;

    // 判斷是否已收藏
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    let isFavorite = favorites.some(item => item.name === exercise.name);

    card.innerHTML = `
        <!-- 標題 + 收藏 -->
        <div class="d-flex justify-content-between align-items-center">
            <h4>${index}. ${exercise.name}</h4>
            <button type="button" 
                    class="btn btn-link p-0 m-0 favorite-btn" 
                    style="color:#f0ad4e; outline:none; box-shadow:none;"
                    onclick='toggleFavorite(${JSON.stringify(exercise)}, this)'>
                <i class="bi ${isFavorite ? "bi-star-fill" : "bi-star"}"></i>
            </button>
        </div>

        <p>針對 ${exercise.target_muscle} 的專業訓練動作</p>

        <!-- 詳情按鈕 -->
        <div class="d-flex justify-content-end mt-2 mb-2">
            <button type="button" 
                    class="p-0 bg-transparent border-0 shadow-none" 
                    data-bs-toggle="modal" 
                    data-bs-target="#exerciseModal"
                    style="line-height:1; outline:none;"
                    onclick='openExerciseModal(${JSON.stringify(exercise)}, ${index})'>
                <i class="bi bi-info-circle text-primary fs-5"></i>
            </button>
        </div>
        
        <!-- 增肌訓練 -->
        <div class="training-details">
            <h5><i class="bi bi-target"></i> 增肌訓練</h5>
            <div class="detail-row">
                <span class="detail-label">組數：</span>
                <span class="detail-value">${exercise.hypertrophy_sets_min}-${exercise.hypertrophy_sets_max} 組</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">次數：</span>
                <span class="detail-value">${exercise.hypertrophy_reps_min}-${exercise.hypertrophy_reps_max} 次</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">重量：</span>
                <span class="detail-value">${hypertrophyWeightMin}-${hypertrophyWeightMax}% 體重</span>
            </div>
        </div>
        
        <!-- 減脂訓練 -->
        <div class="training-details">
            <h5><i class="bi bi-fire"></i> 減脂訓練</h5>
            <div class="detail-row">
                <span class="detail-label">組數：</span>
                <span class="detail-value">${exercise.fatloss_sets_min}-${exercise.fatloss_sets_max} 組</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">次數：</span>
                <span class="detail-value">${exercise.fatloss_reps_min}-${exercise.fatloss_reps_max} 次</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">重量：</span>
                <span class="detail-value">${fatlossWeightMin}-${fatlossWeightMax}% 體重</span>
            </div>
        </div>
    `;
    return card;
}

// 收藏 / 移除收藏
function toggleFavorite(exercise, btn) {
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];

    const exists = favorites.some(item => item.name === exercise.name);

    if (exists) {
        favorites = favorites.filter(item => item.name !== exercise.name);
        alert(`已取消收藏：${exercise.name}`);
        if (btn) btn.querySelector("i").classList.replace("bi-star-fill", "bi-star");
    } else {
        favorites.push(exercise);
        alert(`已加入收藏：${exercise.name}`);
        if (btn) btn.querySelector("i").classList.replace("bi-star", "bi-star-fill");
    }

    localStorage.setItem("favorites", JSON.stringify(favorites));
}
