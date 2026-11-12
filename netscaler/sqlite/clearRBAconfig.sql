delete from ns_sysuser where 1=1;
delete from ns_sysuser_ns_syscmdplcy_binding where 1=1;
delete from ns_sysgroup where 1=1;
delete from ns_sysgroup_ns_sysuser_binding where 1=1;
delete from ns_sysgroup_ns_syscmdplcy_binding where 1=1;
delete from ns_syscmdplcy where 1=1;


delete from ns_vpnvserver_ns_tacacsplcy_binding where 1=1;
delete from ns_authnvserver_ns_tacacsplcy_binding where 1=1;
delete from ns_systemglobal_ns_tacacsplcy_binding where 1=1;
