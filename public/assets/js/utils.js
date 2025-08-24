export function formatCurrency(num) {
  return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 0 }).format(num);
}

export function timeAgo(isoString) {
  const diff = Date.now() - new Date(isoString).getTime();
  const seconds = Math.floor(diff / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);
  if (days > 0) return `${days}d ago`;
  if (hours > 0) return `${hours}h ago`;
  if (minutes > 0) return `${minutes}m ago`;
  return `${seconds}s ago`;
}

export function ageBadgeClasses(days) {
  if (days < 3) return 'bg-gray-100 text-gray-600';
  if (days < 7) return 'bg-amber-100 text-amber-700';
  return 'bg-red-100 text-red-700';
}

export function severityClasses(sev) {
  switch(sev) {
    case 'red': return 'bg-red-50 text-red-600';
    case 'amber': return 'bg-amber-50 text-amber-600';
    case 'green': return 'bg-green-50 text-green-600';
    default: return 'bg-gray-50 text-gray-600';
  }
}
