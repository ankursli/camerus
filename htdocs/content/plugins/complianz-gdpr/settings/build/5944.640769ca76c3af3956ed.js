"use strict";(globalThis.webpackChunkcomplianz_gdpr=globalThis.webpackChunkcomplianz_gdpr||[]).push([[5944],{45944:(e,t,a)=>{a.r(t),a.d(t,{default:()=>z});var n=a(51280),l=a(88496),d=a(88492),r=a(8172),s=a(50800),o=a(63612),c=a(51928),u=a(94484),i=a(79068),D=a(17172),f=a(11768),m=a(53304),p=a(55344),y=a(29296),g=a(40928),w=a(25506),h=a(15832),b=a(93396),_=a(65208);const z=()=>{const[e,t]=(0,l.useState)(null),a=Boolean(e),z=(0,_.default)((e=>e.startDate)),M=(0,_.default)((e=>e.endDate)),E=(0,_.default)((e=>e.setStartDate)),k=(0,_.default)((e=>e.setEndDate)),v=(0,_.default)((e=>e.range)),C=(0,_.default)((e=>e.setRange)),S={startDate:(0,s.c)(z),endDate:(0,s.c)(M),key:"selection"},L=(0,l.useRef)(0),O=["today","yesterday","last-7-days","last-30-days","last-90-days","last-month","last-year","year-to-date"],R={today:{label:(0,b.__)("Today","complianz-gdpr"),range:()=>({startDate:(0,o.default)(new Date),endDate:(0,c.default)(new Date)})},yesterday:{label:(0,b.__)("Yesterday","complianz-gdpr"),range:()=>({startDate:(0,o.default)((0,u.default)(new Date,-1)),endDate:(0,c.default)((0,u.default)(new Date,-1))})},"last-7-days":{label:(0,b.__)("Last 7 days","complianz-gdpr"),range:()=>({startDate:(0,o.default)((0,u.default)(new Date,-7)),endDate:(0,c.default)((0,u.default)(new Date,-1))})},"last-30-days":{label:(0,b.__)("Last 30 days","complianz-gdpr"),range:()=>({startDate:(0,o.default)((0,u.default)(new Date,-30)),endDate:(0,c.default)((0,u.default)(new Date,-1))})},"last-90-days":{label:(0,b.__)("Last 90 days","complianz-gdpr"),range:()=>({startDate:(0,o.default)((0,u.default)(new Date,-90)),endDate:(0,c.default)((0,u.default)(new Date,-1))})},"last-month":{label:(0,b.__)("Last month","complianz-gdpr"),range:()=>({startDate:(0,i.default)((0,D.default)(new Date,-1)),endDate:(0,f.default)((0,D.default)(new Date,-1))})},"year-to-date":{label:(0,b.__)("Year to date","complianz-gdpr"),range:()=>({startDate:(0,m.c)(new Date),endDate:(0,c.default)(new Date)})},"last-year":{label:(0,b.__)("Last year","complianz-gdpr"),range:()=>({startDate:(0,m.c)((0,p.default)(new Date,-1)),endDate:(0,y.c)((0,p.default)(new Date,-1))})}};function T(e){const t=this.range();return(0,g.default)(e.startDate,t.startDate)&&(0,g.default)(e.endDate,t.endDate)}const j=[];for(const[e,t]of Object.entries(O))t&&(j.push(R[t]),j[j.length-1].isSelected=T);const F=e=>{t(null)},N="MMMM d, yyyy",Y=z?(0,w.default)(new Date(z),N):(0,w.default)(defaultStart,N),x=M?(0,w.default)(new Date(M),N):(0,w.default)(defaultEnd,N);return(0,n.createElement)("div",{className:"cmplz-date-range-container"},(0,n.createElement)("button",{onClick:e=>{t(e.currentTarget)},id:"cmplz-date-range-picker-open-button"},(0,n.createElement)(h.default,{name:"calendar",size:"18"}),"custom"===v&&Y+" - "+x,"custom"!==v&&R[v].label,(0,n.createElement)(h.default,{name:"chevron-down"})),(0,n.createElement)(d.cp,{anchorEl:e,anchorOrigin:{vertical:"bottom",horizontal:"right"},transformOrigin:{vertical:"top",horizontal:"right"},open:a,onClose:F,className:"burst"},(0,n.createElement)("div",{id:"cmplz-date-range-picker-container"},(0,n.createElement)(r.wl,{ranges:[S],rangeColors:["var(--rsp-brand-primary)"],dateDisplayFormat:N,monthDisplayFormat:"MMMM",onChange:e=>{(e=>{L.current++;let t=(0,w.default)(e.selection.startDate,"yyyy-MM-dd"),a=(0,w.default)(e.selection.endDate,"yyyy-MM-dd"),n="custom";for(const[t,a]of Object.entries(R))a.isSelected(e.selection)&&(n=t);e.selection.startDate,e.selection.endDate,2!==L.current&&t===a&&"custom"===n||(L.current=0,E(t),k(a),C(n),F())})(e)},inputRanges:[],showSelectionPreview:!0,months:2,direction:"horizontal",minDate:new Date(2022,0,1),maxDate:new Date,staticRanges:j}))))}}}]);