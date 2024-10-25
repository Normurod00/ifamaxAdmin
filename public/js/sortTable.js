
console.log('Sorting script loaded');
    
function sortTable(field, order) {
    let rows = Array.from(document.querySelector("#transactionTable tbody").rows);
    const multiplier = order === 'asc' ? 1 : -1;

    rows.sort((a, b) => {
        const aVal = field === 'time' ? timeToSeconds(a.cells[6].textContent) : parseFloat(a.cells[5].textContent.replace(/,/g, ''));
        const bVal = field === 'time' ? timeToSeconds(b.cells[6].textContent) : parseFloat(b.cells[5].textContent.replace(/,/g, ''));
        return (aVal - bVal) * multiplier;
    });

    rows.forEach(row => document.querySelector("#transactionTable tbody").appendChild(row));
}

function timeToSeconds(timeStr) {
    let [days, hours, minutes, seconds] = timeStr.split(',').map(part => parseFloat(part.match(/\d+/)[0]));
    return ((days * 24 + hours) * 60 + minutes) * 60 + seconds;
}
