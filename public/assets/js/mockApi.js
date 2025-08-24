export async function getAdminDashboardData() {
  const base = window.location.pathname.includes('/admin/') ? '..' : '.';
  const res = await fetch(`${base}/assets/data/admin_dashboard.json`);
  if (!res.ok) throw new Error('Failed to load dashboard data');
  return res.json();
}
