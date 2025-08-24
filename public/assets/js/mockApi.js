export async function getAdminDashboardData() {
  const res = await fetch('/assets/data/admin_dashboard.json');
  if (!res.ok) throw new Error('Failed to load dashboard data');
  return res.json();
}
