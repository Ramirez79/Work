UPDATE ns_feature set nsa_feature=null where 1=1;

UPDATE ns_nsmode set nsa_mode=null where 1=1;
delete from ns_tunnelglobal_ns_tunnelpolicy_binding where 1=1;
delete from ns_vpath where 1=1;
UPDATE ns_vpathparam set nsa_srcip=null where 1=1;
UPDATE ns_vpathparam set nsa_encapmode='L3' where 1=1;
delete from ns_route where nsa_routetype="VPATH";
UPDATE ns_systemparameters set nsa_rbaonresponse='ENABLED', nsa_prompt='', nsa_natpcb_forceflush_connlimit=2147483647, nsa_natpcb_rst_on_timeout='DISABLED', nsa_timeout=900, nsa_maxclient=20, nsa_localauthfirst='ENABLED', nsa_restricted_timeout='DISABLED', nsa_strongpassword='disabled', nsa_minpasswordlen=1, nsa_doppler="0", nsa_google_analytics="DISABLED",nsa_cli_loglevel="INFORMATIONAL" where 1=1;

UPDATE ns_l2param set nsa_mbf_peer_mac_update=10,nsa_max_bdg_collision=20,nsa_adv_linkmtu=0,nsa_bdggrp_proxy_arp="ENABLED",nsa_bdg_setting="DISABLED",nsa_garp_on_vrid_intf="ENABLED",nsa_mac_mode_fwd_my_pkt="DISABLED",nsa_use_my_mac="DISABLED",nsa_proxy_arp="ENABLED",nsa_garp_reply="DISABLED",nsa_mbf_inst_learning="DISABLED",nsa_rst_intf_on_ha_failover="DISABLED",nsa_skip_proxy_bsd_traffic="DISABLED",nsa_ret_to_eth_sender="DISABLED" where 1=1;
UPDATE ns_l3param set nsa_srcnat="ENABLED",nsa_icmp_gen_rate_thrsld=100,nsa_override_rnat="DISABLED",nsa_drop_df_flag="DISABLED",nsa_mip_rr="ENABLED",nsa_extn_loopback="DISABLED",nsa_tnl_pmtu_wo_conn="ENABLED",nsa_usip_srv_straypkt="DISABLED",nsa_forwardicmpfragments="DISABLED",nsa_dropipfragments="DISABLED",nsa_acllogtime=5000,nsa_icmperrgen="ENABLED",nsa_dynamic_routing="DISABLED",nsa_ip6_routing="DISABLED" where 1=1;
