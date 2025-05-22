document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tabs li').forEach(tab => {
        tab.addEventListener('click', () => {
            const sel = tab.dataset.tab;
            // 탭 헤더
            document.querySelectorAll('.tabs li').forEach(t => t.classList.toggle('active', t === tab));
            // 내용 토글
            document.querySelectorAll('.tab-content').forEach(div =>
                div.style.display = (div.id === sel ? 'block' : 'none')
            );
        });
    });
});
