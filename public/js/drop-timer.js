// Counts down every ".drop-timer" on the page.
// It looks for timers every second, so cards added by the live search work too.
function formatTimeLeft(ms) {
    const total = Math.floor(ms / 1000);
    const days = Math.floor(total / 86400);
    const hours = Math.floor((total % 86400) / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const seconds = total % 60;

    const pad = (n) => String(n).padStart(2, '0');
    const clock = pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);

    return days > 0 ? days + 'd ' + clock : clock;
}

function updateDropTimers() {
    document.querySelectorAll('.drop-timer').forEach((timer) => {
        const left = new Date(timer.dataset.end).getTime() - Date.now();

        if (left <= 0) {
            timer.classList.add('is-ended');
            timer.textContent = '⏳ Drop ended';
            return;
        }

        timer.classList.remove('is-ended');
        timer.textContent = '⏳ Ends in ' + formatTimeLeft(left);
    });
}

updateDropTimers();
setInterval(updateDropTimers, 1000);