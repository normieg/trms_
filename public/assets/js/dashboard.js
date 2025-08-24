import { getAdminDashboardData } from './mockApi.js';
import { formatCurrency, timeAgo, ageBadgeClasses, severityClasses } from './utils.js';

document.addEventListener('DOMContentLoaded', async () => {
  try {
    const data = await getAdminDashboardData();
    renderKpis(data.kpis, data.thresholds);
    renderApprovalQueue(data.pendingPRs);
    renderAlerts(data.alerts);
    renderTrendSvg(data.monthlyCosts);
    renderRankings(data.rankings);
    renderLowStock(data.lowStock);
    renderActivity(data.activity);
  } catch (e) {
    console.error(e);
  }
});

function renderKpis(kpis, thresholds){
  const wrapper = document.getElementById('kpi-cards');
  if(!wrapper) return;
  const items = [
    { label:'Fleet Size', value:kpis.fleetSize, icon:'🚚'},
    { label:'In-service %', value:`${kpis.inServicePct}%`, icon:'🛠️'},
    { label:'Trucks In Repair', value:kpis.trucksInRepair, icon:'🔧'},
    { label:'MTD Maintenance Cost', value:formatCurrency(kpis.mtdCost), icon:'💰'},
    { label:'QoQ % Change', value:`${kpis.qoqChangePct}%`, icon:'📉', change:kpis.qoqChangePct},
    { label:'MTD Downtime (hrs)', value:kpis.mtdDowntimeHrs, icon:'⏱️'}
  ];
  wrapper.innerHTML = items.map(item => {
    let changeChip='';
    if(item.label==='QoQ % Change'){
      const dir = item.change >0 ? '▲' : '▼';
      const col = item.change >0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600';
      changeChip = `<span class="ml-2 px-2 py-0.5 text-xs rounded ${col}">${dir}</span>`;
    }
    return `<div class="rounded-2xl shadow-sm p-5 bg-white flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">${item.label}</p>
        <p class="text-xl font-semibold">${item.value}${changeChip}</p>
      </div>
      <span class="text-2xl" aria-hidden="true">${item.icon}</span>
    </div>`;
  }).join('');

  // thresholds mini card
  const settings = document.getElementById('settings-card');
  settings.innerHTML = `<div class="rounded-2xl shadow-sm p-5 bg-white">
    <h3 class="font-semibold mb-2">Alert Thresholds</h3>
    <ul class="text-sm space-y-1">
      <li>Cost MoM &gt; ${thresholds.costMoM}%</li>
      <li>Repairs in 90d &gt; ${thresholds.repairs90d}</li>
      <li>Downtime 30d &gt; ${thresholds.downtime30dHrs} hrs</li>
    </ul>
    <button class="mt-3 px-3 py-1.5 bg-gray-200 text-gray-600 rounded cursor-not-allowed" disabled>Edit thresholds</button>
  </div>`;
}

function renderApprovalQueue(prs){
  const body = document.querySelector('#approval-queue tbody');
  if(!body) return;
  if(!prs.length){
    body.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-sm text-gray-500">No pending requests</td></tr>`;
    return;
  }
  body.innerHTML = prs.map(pr => {
    const ageDays = Math.floor((Date.now() - new Date(pr.createdAt)) / (1000*60*60*24));
    return `<tr class="hover:bg-gray-50 focus-within:bg-gray-50">
      <td class="p-2">${pr.prNo}</td>
      <td class="p-2">${pr.requesterName}</td>
      <td class="p-2 text-center">${pr.itemsCount}</td>
      <td class="p-2 text-right">${formatCurrency(pr.estTotal)}</td>
      <td class="p-2"><span class="px-2 py-1 rounded text-xs ${ageBadgeClasses(ageDays)}">${ageDays}d</span></td>
      <td class="p-2 text-center"><button disabled class="px-2 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed" title="Review disabled">Review</button></td>
    </tr>`;
  }).join('');
}

function renderAlerts(alerts){
  const list = document.getElementById('alerts-list');
  const tabs = document.getElementById('alert-tabs');
  let current = 'all';

  function update(){
    const filtered = alerts.filter(a => current==='all' ? true : a.severity===current);
    if(!filtered.length){
      list.innerHTML = `<li class="text-sm text-gray-500 p-4">No alerts</li>`;
      return;
    }
    list.innerHTML = filtered.map(a => `<li class="p-4 border-b last:border-0 flex justify-between" tabindex="0">
      <div>
        <p class="font-medium">${a.truckPlate} <span class="ml-2 px-2 py-0.5 text-xs rounded ${severityClasses(a.severity)}">${a.severity}</span></p>
        <p class="text-sm text-gray-600">${a.message}</p>
      </div>
      <time class="text-xs text-gray-500">${timeAgo(a.createdAt)}</time>
    </li>`).join('');
  }

  tabs.addEventListener('click', e => {
    const btn = e.target.closest('button');
    if(!btn) return;
    current = btn.dataset.filter;
    [...tabs.querySelectorAll('button')].forEach(b=>b.classList.remove('border-b-2','border-blue-500'));
    btn.classList.add('border-b-2','border-blue-500');
    update();
  });
  update();
}

function renderTrendSvg(monthly){
  const container = document.getElementById('cost-trend');
  if(!container) return;
  const w=600, h=240, padding=30;
  const maxTotal = Math.max(...monthly.map(m=>m.total));
  const xStep = (w-padding*2)/(monthly.length-1);
  let d="";
  monthly.forEach((m,i)=>{
    const x = padding + i*xStep;
    const y = h - padding - (m.total/maxTotal)*(h-padding*2);
    d += i===0?`M${x},${y}`:` L${x},${y}`;
  });
  const bars = monthly.map((m,i)=>{
    const x = padding + i*xStep - 10;
    const partsHeight = (m.parts/maxTotal)*(h-padding*2);
    const laborHeight = (m.labor/maxTotal)*(h-padding*2);
    const yParts = h - padding - partsHeight;
    const yLabor = yParts + (partsHeight - laborHeight);
    return `<g>
      <rect x="${x}" y="${yParts}" width="8" height="${partsHeight}" class="fill-blue-200"></rect>
      <rect x="${x+8}" y="${yLabor}" width="8" height="${laborHeight}" class="fill-blue-400"></rect>
    </g>`;
  }).join('');

  container.innerHTML = `<svg viewBox="0 0 ${w} ${h}" aria-label="Maintenance cost trend"><desc>Total vs Parts vs Labor for last 12 months</desc>
    <path d="${d}" fill="none" stroke="#2563eb" stroke-width="2" />
    ${bars}
  </svg>`;
}

function renderRankings(rankings){
  const high = document.querySelector('#rank-high tbody');
  const repairs = document.querySelector('#rank-repairs tbody');
  if(!rankings.highestCost.length) {
    high.innerHTML = `<tr><td colspan="4" class="text-center text-sm text-gray-500 p-2">No data</td></tr>`;
  } else {
    high.innerHTML = rankings.highestCost.map(r=>`<tr class="hover:bg-gray-50">
      <td class="p-2">${r.rank}</td>
      <td class="p-2">${r.truckPlate}</td>
      <td class="p-2">${r.model}</td>
      <td class="p-2 text-right">${formatCurrency(r.value)}</td>
    </tr>`).join('');
  }
  if(!rankings.mostRepairs.length) {
    repairs.innerHTML = `<tr><td colspan="4" class="text-center text-sm text-gray-500 p-2">No data</td></tr>`;
  } else {
    repairs.innerHTML = rankings.mostRepairs.map(r=>`<tr class="hover:bg-gray-50">
      <td class="p-2">${r.rank}</td>
      <td class="p-2">${r.truckPlate}</td>
      <td class="p-2">${r.model}</td>
      <td class="p-2 text-right">${r.value}</td>
    </tr>`).join('');
  }
}

function renderLowStock(items){
  const list = document.getElementById('low-stock-list');
  if(!items.length){
    list.innerHTML = `<li class="text-sm text-gray-500 p-4">No low stock items</li>`;
    return;
  }
  list.innerHTML = items.map(i=>`<li class="p-4 border-b last:border-0">
    <p class="font-medium">${i.name} <span class="text-xs text-gray-500">(${i.sku})</span></p>
    <p class="text-sm text-gray-600">On hand: ${i.onHand} | Min: ${i.minLevel}</p>
    <p class="text-xs text-gray-500">Locations: ${i.locations.join(', ')}</p>
  </li>`).join('');
}

function renderActivity(items){
  const list = document.getElementById('activity-feed');
  if(!items.length){
    list.innerHTML = `<li class="text-sm text-gray-500 p-4">No recent activity</li>`;
    return;
  }
  list.innerHTML = items.map(a=>`<li class="p-4 border-b last:border-0 flex justify-between" tabindex="0">
    <span class="mr-2" aria-hidden="true">${iconFor(a.icon)}</span>
    <p class="flex-1 text-sm">${a.text}</p>
    <time class="text-xs text-gray-500">${timeAgo(a.timestamp)}</time>
  </li>`).join('');
}

function iconFor(type){
  switch(type){
    case 'ticket': return '🎫';
    case 'wo': return '🧰';
    case 'pr': return '📝';
    default: return 'ℹ️';
  }
}
