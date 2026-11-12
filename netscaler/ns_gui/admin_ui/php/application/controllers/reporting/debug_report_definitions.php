<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class debug_report_definitions
{
    //Define leaves of tree (reports) in this format
    var $dbg_crit0 = array(
        "name"      =>  "CPU Details",
        "key"       =>  "dbg_crit0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "master_cpu_use,slave_cpu_use,sys_cpus")));

    var $dbg_crit1 = array(
        "name"      =>  "Memory Details",
        "key"       =>  "dbg_crit1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "sys_memorysize_MB,ns_memorysize_MB,mem_cur_usedsize,mem_cur_freesize,mem_cur_freesize_actual,sys_cur_freensbs"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_tot_sslInfo_SessionNodeFreeCount,ssl_tot_sslInfo_SPCBFreeCount,cacmem_cur_freenetbuffs,tcp_cur_freenetbuffs,pcb_cur_free,natpcb_cur_freecnt")));

    var $dbg_crit2 = array(
        "name"      =>  "Memory Errors",
        "key"       =>  "dbg_crit2",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "pcb_err_dupfreed,nsb_err_dupfreed,mem_err_dupfreed,b64_err_dupfreed,mem_err_ssl_spcb_dupfreed"),
                              array("id"    =>  2,
                                    "cntrs" =>  "dns_ferr_dupfreed,mem_err_ssl_session_dupfreed,lb_tot_session_dup_freed,mem_err_badaddrfreed,natpcb_ferr_double_free")));

    var $dbg_crit3 = array(
        "name"      =>  "Memory Leaks",
        "key"       =>  "dbg_crit3",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "sys_cur_nsbs,sys_cur_freensbs"),
                              array("id"    =>  2,
                                    "cntrs" =>  "pcb_cur_alloc,pcb_cur_free,pcb_cur_inuse"),
                              array("id"    =>  3,
                                    "cntrs" =>  "natpcb_cur_allocated,natpcb_cur_freecnt,natpcb_cur_inuse")));

    var $dbg_crit4 = array(
        "name"      =>  "HA Errors",
        "key"       =>  "dbg_crit4",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ha_err_master_dispute,ha_err_nodes_down,ha_err_sync_failure,ha_err_sync_failure")));

    var $dbg_crit5 = array(
        "name"      =>  "System Errors",
        "key"       =>  "dbg_crit5",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "cur_syshealth_disk0_errors,cur_syshealth_disk1_errors,fbsd_err_forkfail_syslimit,fbsd_err_forkfail_rulimit")));

    var $dbg_crit6 = array(
        "name"      =>  "Hardware Errors",
        "key"       =>  "dbg_crit6",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "nic_err_bdg_muted,nic_err_link_hangs,nic_err_duplex_mismatch,ssl_err_cardstatusdown")));

    var $dbg_crit7 = array(
        "name"      =>  "Other Errors",
        "key"       =>  "dbg_crit7",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "cacout_err_buf_cache_clash,ipv6_err_IPv6tov4_map_failure")));

    var $dbg_mem0 = array(
        "name"      =>  "Errors 1",
        "key"       =>  "dbg_mem0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "sys_err_out_of_nsbs,sys_err_nsb_hold_limit,mem_err_alloc_failed,mem_err_alloc_failed_size,mem_err_limitfailed,mem_err_limitfailed_size,mem_err_freefailed,mem_err_freefailed_size"),
                              array("id"    =>  2,
                                    "cntrs" =>  "mem_err_failedos,mem_err_pcbpages_allocfailed,mem_err_pcbpages_freetimedout,mem_err_natpcbpages_allocfailed,mem_err_natpcbpages_freetimedout,mem_err_nsbpages_allocfailed,mem_err_nsbpages_freetimedout,mem_err_dnsfullpages_allocfailed")));

    var $dbg_mem1 = array(
        "name"      =>  "Errors 2",
        "key"       =>  "dbg_mem1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "mem_err_dnsfullpages_freetimedout,mem_err_dnssmallpages_allocfailed,mem_err_dnssmallpages_freetimedout,mem_err_b64pages_allocfailed,mem_err_b64pages_freetimedout,mem_err_ssl_sessionpages_freetimedout,mem_err_ssl_sessionpages_allocfailed,mem_err_ssl_spcb_pages_allocfailed"),
                              array("id"    =>  2,/*mem_err_ccbpages_allocfailed*/
                                    "cntrs" =>  "mem_tot_ssl_spcb_pages_freetimedout,mem_err_devnamerecpages_allocfailed,mem_err_mon_info_page_allocfailed,arp_err_alloc_failed,arp_err_nsballoc_failed,mem_tot_lioctl_alloc_failed,html_inj_mem_alloc_failed")));

    var $dbg_mon0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_mon0",
        "elements"  =>  array(array("id"    =>  1,/*mon_tot_failed_timeout*/
                                    "cntrs" =>  "mon_tot_maxclients,mon_tot_failed_con,mon_tot_failed_send,mon_cur_rtothresh_exceeded"),
                              array("id"    =>  2,
                                    "cntrs" =>  "monitor_err_user_cpu_fail,monitor_err_user_file_limit,monitor_err_user_proc_limit,monitor_tot_pcb_failed,monitor_tot_failed")));

    var $dbg_mon1 = array(
        "name"      =>  "Server Specific Issues",
        "key"       =>  "dbg_mon1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "mon_tot_failed_code,mon_tot_failed_string,mon_tot_failed_port,mon_tot_failed_response,mon_tot_failed_response_id")));

    var $dbg_http0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_http0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "http_err_IncompleteHeaders,http_err_IncompleteNoMemory,http_err_get_dropped,http_err_old_data"),
                              array("id"    =>  2,
                                    "cntrs" =>  "http_err_bad_seq,http_err_bad_synack_linkabort,http_err_redirect_url_truncated,http_err_noreuse_IncompleteHeader")));

    var $dbg_http1 = array(
        "name"      =>  "Client Specific Issues",
        "key"       =>  "dbg_http1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "http_err_IncompleteRequests,http_err_ooo_packets,http_err_bad_get")));

    var $dbg_http2 = array(
        "name"      =>  "Server Specific Issues",
        "key"       =>  "dbg_http2",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "http_err_IncompleteResponses,http_err_morethan_ctlen")));

    var $dbg_http3 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_http3",
        "elements"  =>  array(array("id"    =>  1,/*http_http_err_extracrlf_flush*/
                                    "cntrs" =>  "http_err_extra_crlfs,http_err_large_content,http_err_large_ctlen,http_err_large_chunk")));

    var $dbg_ha0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_ha0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ha_err_prop_timeout,ha_tot_recovers_num,ha_err_nsb_mem_fail,ha_err_sw_monitor_fail,ha_err_pkt_rx_dst_mac,ha_err_pkt_rx_sig,ha_err_pkt_rx_ver,ha_err_pkt_rx_sn"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ha_err_prop_mem_fail,ha_err_pkt_rx_not_init,ha_err_cfgsync_failed_ioctls,ha_err_heartbeat,ha_err_bad_app_id,ha_err_bad_type,ha_err_bad_state,ha_err_bad_master_state,ha_err_bad_packet_length")));

    var $dbg_ssl0 = array(
        "name"      =>  "Frontend Handshake Errors",
        "key"       =>  "dbg_ssl0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_ssl3_send_server_hello,ssl_err_ssl3_send_server_certificate,ssl_err_ssl3_send_server_key_exchange,ssl_err_ssl3_send_certificate_request,ssl_err_ssl3_send_server_done,ssl_err_ssl3_send_change_cipher_spec,ssl_err_ssl3_send_finished")));

    var $dbg_ssl1 = array(
        "name"      =>  "Client Specific Issues",
        "key"       =>  "dbg_ssl1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_ssl3_get_client_certificate,ssl_err_ssl3_get_client_key_exchange,ssl_err_ssl3_get_change_cipher_spec,ssl_err_ssl3_get_cert_verify,ssl_err_ssl3_get_finished"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_err_ssl3_get_client_hello,ssl_err_ssl2_get_client_hello,ssl_err_ssl2_get_client_master_key,ssl_err_ssl2_get_client_finished,ssl_err_ssl2_get_client_certificate")));

    var $dbg_ssl2 = array(
        "name"      =>  "Backend Handshake Errors",
        "key"       =>  "dbg_ssl2",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_Backend_ssl3_send_client_hello,ssl_err_Backend_ssl3_send_client_keyEx,ssl_err_Backend_ssl3_send_client_CCS,ssl_err_Backend_ssl3_send_client_finish,ssl_err_Backend_ssl3_send_client_cert,ssl_err_Backend_ssl3_send_client_certverify")));

    var $dbg_ssl3 = array(
        "name"      =>  "Server Specific Issues",
        "key"       =>  "dbg_ssl3",
        "elements"  =>  array(array("id"    =>  1,/*ssl_back_hskssl_err_Backend_ssl3_get_server_cert*/
                                    "cntrs" =>  "ssl_err_Backend_ssl3_get_server_hello,ssl_err_Backend_ssl3_get_server_keyEx,ssl_err_Backend_ssl3_get_server_done,ssl_err_Backend_ssl3_get_server_CCS,ssl_err_Backend_ssl3_get_server_finish,ssl_err_Backend_ssl3_get_server_cert_request")));

    var $dbg_ssl4 = array(
        "name"      =>  "Card Errors 1",
        "key"       =>  "dbg_ssl4",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_ssl_ubsec_unaligned_data_addr,ssl_err_ssl_ubsec_unaligned_data_len,ssl_err_ssl_ubsec_device_busy,ssl_err_ssl_ubsec_dma_error,ssl_err_no_fips_rsapubkey_for_cavium_monitoring,ssl_err_cavium_public_encrypt_nocrt,ssl_err_cavium_monitorrsagenkeypair,ssl_err_cavium_allocrespbuffailed"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_err_cavium_cfm1initialise_failed,ssl_err_cavium_cfm1loginHSM_failed,ssl_err_cavium_cfm1AllocContext_failed,ssl_err_cavium_cfm1GenerateSymmKey_failed,ssl_err_cavium_Cfm1Initialize3DES,ssl_err_cavium_Cfm1ImportSymmetricKey_sslv2,ssl_err_cavium_Cfm1ImportSymmetricKey_unknown_cipher,ssl_err_cavium_Cfm1ImportSymmetricKey_sslv3_3des")));

    var $dbg_ssl5 = array(
        "name"      =>  "Card Errors 2",
        "key"       =>  "dbg_ssl5",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_cavium_Cfm1ImportSymmetricKey_sslv3_aes,ssl_err_luna_open_session,ssl_err_fips_Cfm1InitHSM_failed,ssl_err_failed_to_alloc_cavium_monitor_st,ssl_err_no_fips_rsaprivkey_for_cavium_monitoring,ssl_error_cavium_hsmerror,ssl_error_cavium_delete_cmd_pending,ssl_error_cavium_memory_alloc_failure"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_error_cavium_driver_error,ssl_error_cavium_unknown_error,ssl_error_cavium_command_invalid,ssl_error_cvm_cmd_timeout,ssl_error_cvm_badpkcspad_length,ssl_err_fips_loginhsm_oldsopin_failed,ssl_err_fips_loginhsm_default_failed,ssl_err_fips_fipskey_add_failed")));

    var $dbg_ssl6 = array(
        "name"      =>  "Card Errors 3",
        "key"       =>  "dbg_ssl6",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_fips_fipskey_sign_failed,ssl_err_fips_sim_Cfm1GetTWC_failed,ssl_err_fips_sim_cert_too_big,ssl_err_fips_sim_Cfm1CloneObjectTargetInit_failed,ssl_err_fips_sim_Cfm1CloneObjectAsTarget_failed,ssl_err_fips_sim_Cfm1CloneObjectAsSource_failed,ssl_err_nsb_alloc_failed_cvm,ssl_err_nsb_hold_failed_cvm")));

    var $dbg_ssl7 = array(
        "name"      =>  "Other Errors 1",
        "key"       =>  "dbg_ssl7",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_ssl_state_disabled,ssl_tot_sslError_FatalAlertRecdCount,ssl_tot_sslError_FatalAlertSentCount,ssl_tot_sslError_KeyEx_RSA,ssl_tot_sslError_KeyEx_DH,ssl_tot_sslError_HalfCloseCount,ssl_tot_sslError_PrematureCloseCount,ssl_err_ssl3_block_cipher_pad_wrong"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_err_tls_block_cipher_pad_wrong,ssl_err_tls_decryption_failed,ssl_err_ssl3_spcb_alloction,ssl_err_ssl3_ssl23_read,ssl_err_ssl3_ssl23_peek,ssl_err_ssl3_ssl23_write,ssl_err_ssl_ssl23_sent_rst,ssl_err_ssl2_mss_neg_error")));

    var $dbg_ssl8 = array(
        "name"      =>  "Other Errors 2",
        "key"       =>  "dbg_ssl8",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ssl_err_ssl2_nsb_hold,ssl_err_ssl3_bad_mac_decode,ssl_err_ssl3_app_data_in_hshk,ssl_err_decryption_failed_bad_mac_decode,ssl_err_nonsb,ssl_err_Backend_handskmsg_after_estb,ssl_err_create_mon_ctx,ssl_err_alert_nsb_len_less_2,ssl_err_outputnsb_notavail"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_err_nonsb_for_output,ssl_error_nsballoc_failed,ssl_error_cvm_bad_record,ssl_error_cvm_badsize_dlen,ssl_err_finprocess_nsballoc_failed,ssl_err_crl_memlim_exceeded,ssl_err_crl_memalloc_failed,ssl_err_crl_invalidchunkstate,ssl_err_polreneg_heldnsb_count_exceeded")));

    var $dbg_ssl9 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_ssl9",
        "elements"  =>  array(array("id"    =>  1,/*ssl_sslError_RecordLenZero*/
                                    "cntrs" =>  "ssl_tot_sslError_RecordIncompCount,ssl_tot_sslError_RecordTooLargeCount,ssl_tot_sslError_MissingClient,ssl_tot_sslError_RecordTooSmall,ssl_tot_sslError_BadRecord,ssl_err_ssl2_bad_mac_decode,ssl_err_ssl2_invld_challenge_length,ssl_err_ssl2_bad_session_id_len"),
                              array("id"    =>  2,
                                    "cntrs" =>  "ssl_err_ssl2_clnt_finsh_wng_packet_type,ssl_err_ssl2_clnt_fnsd_connid_mismatch,ssl_err_ssl3_got_fnsh_b4_ccs,ssl_err_ssl3_get_msg_ivld_msg_size,ssl_err_ssl3_get_rcd_wng_version,ssl_err_ssl3_get_rcd_len_overflow,ssl_err_ssl3_data_btw_css_and_fnsh")));

    var $dbg_nic0 = array(
        "name"      =>  "Errors 1",
        "key"       =>  "dbg_nic0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "nic_err_rx,nic_err_tx,nic_err_dropped_pkts,nic_err_tx_dropped,nic_err_rx_nonsb,nic_err_rx_crc,nic_err_rx_long_frame,nic_err_rx_alignment"),
                              array("id"    =>  2,
                                    "cntrs" =>  "nic_err_ifInDiscards,nic_err_ifOutDiscards,nic_err_link_reinits,nic_err_link_sts_stalls,nic_err_link_tx_stalls,nic_err_link_rx_stalls,nic_err_link_errdisables,nic_err_tx_nonucpkt")));

    var $dbg_nic1 = array(
        "name"      =>  "Errors 2",
        "key"       =>  "dbg_nic1",
        "elements"  =>  array(array("id"    =>  1, /*nic_err_rx_nobufs,nic_err_rx_fifo*/
                                    "cntrs" =>  "nic_err_tx_collisions,nic_err_tx_excess_collisions,nic_err_tx_late_collisions,nic_err_tx_multi_collision_frame,nic_err_tx_carrier,nic_err_congested_pkts_dropped,nic_err_congestionlimit_pkts_dropped,nic_err_rx_FCS,nic_err_tx_fifo")));

    var $dbg_udp0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_udp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "udp_err_dropped,udp_err_badchecksums,udp_err_threshold")));

    var $dbg_icmp0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_icmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "icmp_err_threshold,icmpgen_err_threshold,icmp_err_dropped,icmp_err_pmtu_dfonfrag,icmp_err_pmtu_cksum,icmp_err_pmtu_disabled")));

    var $dbg_icmp1 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_icmp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "icmp_err_badchecksums,icmp_err_pmtu_invalbodylen,icmp_err_pmtu_invalseqno,icmp_err_nextmtu_inval,icmp_err_bignxtmtu,icmp_err_pmtu_unknownproto")));

    var $dbg_ip0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_ip0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ip_err_natvip_down,ip_err_landattack,ip_err_zeroport")));

    var $dbg_ip1 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_ip1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "ipv6_err_rxhdr,ipv6_err_rxpkt,ipv6_err_rxnxthdr,ipv6_err_badcksum,ipv6_err_rxicmp")));

    var $dbg_tcp0 = array(
        "name"      =>  "Errors 1",
        "key"       =>  "dbg_tcp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "tcp_err_any_portalloc_failed,tcp_err_ip_portalloc_failed,tcp_err_rst,tcp_err_send_rst,tcp_err_rst_clnt,tcp_err_rst_not_est,tcp_err_rst_pass,tcp_err_rst_pass_clnt"),
                              array("id"    =>  2,
                                    "cntrs" =>  "tcp_err_rst_pass_srvr,tcp_err_orphan_max_pkt,tcp_err_cookie_no_pcb,tcp_err_nobuf,tcp_err_clt_nopcb_limit,tcp_err_svr_nopcb_limit,tcp_err_pcblimited,tcp_err_usip_onearm")));

    var $dbg_tcp1 = array(
        "name"      =>  "Errors 2",
        "key"       =>  "dbg_tcp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "tcp_err_bdg_muted,tcp_err_dup_portfree,tcp_err_noip_portfree,tcp_err_stray_packets,tcp_err_in_congestion_syndropped,tcp_err_ooo_maxpkt,tcp_tot_acl_treebuilderr"),
                              array("id"    =>  2,
                                    "cntrs" =>  "tcp_err_ip_ftprange_portalloc_failed,tcpb_err_bypass,tcpb_err_headalloc_failed,tcp_tot_log_bufOverruncount,tcp_tot_syn,tcp_tot_ClientOpen")));

    var $dbg_tcp2 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_tcp2",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "tcp_err_dataAfterFin,tcp_err_syn_n_syn_rcvd,tcp_err_syn_n_est,tcp_err_cookie_pkt_seq_reject,tcp_err_cookie_signature_reject,tcp_err_cookie_pkt_seq_drop,tcp_err_cookie_mss,tcp_err_abrupt_clnt_close,tcp_err_abrupt_srvr_close")));

    var $dbg_arp0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_arp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "arp_err_nsbfreed,arp_err_threshold,arp_err_ipaddr_conflicts,arp_err_subnet_mismatch,arp_err_vlan_mismatch,arp_err_vlanresolve_failed")));

    var $dbg_arp1 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_arp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "arp_err_bad_pkts,arp_err_dup_pkts,arp_err_broadcast_srcaddr,arp_err_vlan_req")));

    var $dbg_csw0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_csw0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "csw_err_trans_si_lookup_failed,csw_err_parse_failed,csw_err_rev_map_lookup_failed,csw_err_400,csw_err_503,csw_err_404,csw_err_dns_lookup_failed,csw_err_default_lookup_failed,csw_err_no_host_no_default")));

    var $dbg_snmp0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_snmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "snmp_err_alloc_fails,snmp_err_child2leaf,snmp_err_dup_nodes,snmp_err_no_nodes,snmp_err_no_links,snmp_err_req_dropped,snmp_err_setnomem,snmp_err_trapnsballocfailed,snmp_tot_parseErrs,snmp_err_avlnomem,snmp_tot_decryptionErrors")));

    var $dbg_cmp0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_cmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "deltacmp_err_basefile_writehdr_failed,deltacmp_err_basefile_nostore_miss,deltacmp_err_nodelta_session,deltacmp_err_deltajs_notready,decmp_err_data,decmp_err_less_data,decmp_err_more_data"),
                              array("id"    =>  2,
                                    "cntrs" =>  "decmp_err_unknown,decmp_err_memory,deltacmp_err_reqinfo_toobig,deltacmp_err_reqinfo_allocfail,deltacmp_err_session_allocfail,cmp_err_pkt_dropped,cmp_err_mismatch_ae,cmp_tcp_err_eoi_in_timerq")));

    var $dbg_pi0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_pi0",/*rw_err_config_changed_when_blocked,rsp_err_config_changed_when_blocked,rw_tot_ctlen_update_failed,async_tot_pipeline_splitPkt_failed*/
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "rsp_err_action_trigger_failed,pipal_err_pipal_policies_list_alloc_failed,async_err_send_100continue_failed,rw_warn_change_data_failed,rw_warn_header_insert_failed,rsp_pipal_eval_failed,rsp_warn_redirect_failed,rsp_warn_respondwith_failed,async_tot_ctx_alloc_failed,pixl_err_blob_size_too_huge")));

    var $dbg_audit0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_audit0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "auditlog32_err_syslog_allocnatpcb,auditlog32_err_syslog_allocnsb,auditlog32_err_createcontext_allocmem,auditlog32_err_syslog_allocport,auditlog32_err_syslog_contextnotfound,auditlog32_err_audserv_allocnsbchain,auditlog32_err_audserv_clientconnectfailed")));

    var $dbg_lb0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_lb0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "lb_err_gslb_sp_found,lb_err_gslb_ldns_loc_notfound,lb_err_gslb_sp_nomatch,lb_err_spillover_persist_change,lb_err_spillover_no_vip,lb_err_gslb_sitepersist_cookieinsertfail,lb_sip503_errthreshold,lb_err_sasp_nsb_alloc_failed,lb_err_sasp_msgid_match_not_found,lb_err_sasp_regstr_err"),
                              array("id"    =>  2,
                                    "cntrs" =>  "lb_err_sasp_wrong_msg_type,lb_err_sasp_deregstr_err,lb_err_sasp_getwght_err,lb_err_sasp_setmembr_st_err,lb_err_sasp_flagset_nomsg,lb_err_sasp_hdr_not_present,lb_err_sasp_setlbstate_err,lb_err_sasp_dereg_on_wght_msg,gslb_tot_gslb_nsballoc_failed,gslb_tot_ldns_mem_alloc_fail_cnt,gslb_err_backup_persist_no_vip")));

    var $dbg_vpn0 = array(
        "name"      =>  "Errors 1",
        "key"       =>  "dbg_vpn0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "svpn_err_sso_false_401_triggers,svpn_err_sso_delegation_to_client,svpn_err_sso_mitm_attack,svpn_err_sso_ntlm_malicious_2nd_401,svpn_err_sso_ck_dmn_mismatch,svpn_err_sso_ck_path_mismatch,svpn_err_sso_set_ck_override,svpn_tot_icmp_min_data_err,svpn_icmp_mem_alloc_err,svpn_icmp_datalen_exceeded_err,svpn_icmp_max_data_exceeded_err"),
                              array("id"    =>  2,
                                    "cntrs" =>  "svpn_icmp_nat_err,svpn_tot_failover_vpnvip_err,svpn_tot_failover_dnsvip_err,svpn_tot_failover_aaauser_err,svpn_tot_failover_aaagrp_err,svpn_tot_failover_iip_err,svpn_tot_failover_sess_act_err,svpn_tot_failover_csec_err,svpn_tot_failover_modify_csec_err,svpn_tot_failover_packing_err,svpn_tot_failover_sess_add_err")));

    var $dbg_vpn1 = array(
        "name"      =>  "Errors 2",
        "key"       =>  "dbg_vpn1",
        "elements"  =>  array(array("id"    =>  1,/*svpn_tot_failover_send_sync_errc*/
                                    "cntrs" =>  "svpn_tot_failover_sess_inherit_err,svpn_tot_failover_sess_reg_svc_err,svpn_tot_failover_async_add_err,svpn_tot_failover_sess_ver_mismatch_err,svpn_tot_failover_async_clear_err,svpn_tot_failover_csec_expr_err,svpn_tot_duplicate_session_prop_err,svpn_fs_vpnd_notup_err,svpn_epa_collect_err,aaa_tot_aaad_protocol_error,iip_err_iplwde_alloc_failed"),
                              array("id"    =>  2,
                                    "cntrs" =>  "iip_err_conf_invalid_netid,iip_err_conf_not_contiguous_mask,iip_err_conf_ns_owned_ip,iip_err_conf_user_ipb_overlap,iip_err_conf_group_ipb_overlap,iip_err_conf_vsvr_ipb_overlap,iip_err_conf_glbl_ipb_overlap,iip_err_conf_pgtbl0_alloc_failures,iip_err_conf_ipblock_alloc_failures,iip_err_pgtbl_explode_impossible")));

    var $dbg_vpn2 = array(
        "name"      =>  "Client Server Specific Issues",
        "key"       =>  "dbg_vpn2",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "svpn_err_sso_set_ck2_no_version,svpn_err_sso_set_ck2_wrong_version,svpn_err_sso_set_ck_parse_error,svpn_err_sso_very_long_ck_hdr,svpn_err_sso_very_long_set_ck_hdr,socks_server_err,socks_client_err")));

    var $dbg_dns0 = array(
        "name"      =>  "Errors 1",
        "key"       =>  "dbg_dns0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "dns_err_ResponseClassUnsupported,dns_err_ResponseTypeUnsupported,dns_err_decimalcoding,dns_err_bigdecimalcoding,dns_err_bad_labelencodings,dns_err_loop_labelencodings,dns_err_unknown_labelencodings,dns_err_QueryClassUnsupported,dns_err_bigqueries,dns_err_QueryFormats"),
                              array("id"    =>  2,
                                    "cntrs" =>  "dns_err_badrequestcounts,dns_err_QueryNoSpace,dns_err_NonAuthUsingAuthEntry,dns_err_AAAA_AliasExists,dns_err_A_AliasExists,dns_err_NS_AliasExists,dns_err_MX_AliasExists,dns_err_SOA_AliasExists,dns_err_PTR_AliasExists,dns_err_CNAME_AliasExists")));

    var $dbg_dns1 = array(
        "name"      =>  "Errors 2",
        "key"       =>  "dbg_dns1",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "dns_err_SRV_AliasExists,dns_err_MXPreferenceInUse,dns_err_HostLimits,dns_err_DomainLimits,dns_err_NoDomains,dns_err_aaaaNoDomains,dns_err_aNoDomains,dns_err_nsNoDomains,dns_err_mxNoDomains,dns_err_soaNoDomains"),
                              array("id"    =>  2,
                                    "cntrs" =>  "dns_err_cnameNoDomains,dns_err_ptrNoDomains,dns_err_srvNoDomains,dns_err_anyNoDomains,dns_err_resolve_requested_ontimeout,dns_err_resolve_timedout,dns_err_resolve_noresolutions,dns_tot_OtherErrors,dns_err_multiquery_disabled")));

    var $dbg_dns2 = array(
        "name"      =>  "Server Specific Issues",
        "key"       =>  "dbg_dns2",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "dns_err_ResponseWithoutAnswers,dns_err_ResponseBadLength,dns_err_zerolengthlabelnames,dns_err_biglabelnames,dns_err_bigdnsnames,dns_err_ResponseFormats,dns_err_domainformats,dns_err_ResponseBadIPAddresses"),
                              array("id"    =>  2,
                                    "cntrs" =>  "dns_err_ResponseCode_Format,dns_err_ResponseCode_Name,dns_err_ResponseCode_Server,dns_err_ResponseCode_NotImplemented,dns_err_ResponseCode_Refused,dns_err_ResponseCode_Unknown,dns_err_ResponseForAuthEntry,dns_err_ServerResponses_BadID,dns_err_big_domain_plus_suffix_names")));

    var $dbg_poleng0 = array(
        "name"      =>  "Errors",
        "key"       =>  "dbg_poleng0",
        "elements"  =>  array(array("id"    =>  1,
                                    "cntrs" =>  "poleng_tot_err_free_sess_abrpt,poleng_tot_err_pending_polrefct,poleng_tot_err_pending_cpess,poleng_tot_err_meminherit_fail,poleng_tot_err_memdupalloc_fail,poleng_tot_err_memmerge_fail,poleng_tot_err_meminhupdate_fail,poleng_tot_alloc_fail_sessions,poleng_tot_err_memunit_alloc_fail,poleng_tot_err_memrep_alloc_fail,poleng_tot_err_memfeature_alloc_fail")));

    //Debug reports is made up of nodes and leaves
    var $debug_reports;
    public function __construct()
    {
$this->debug_reports = array(
    array("name"  =>  "Critical",
          "key"   =>  "dbg_crit",
          "nodes" =>  array($this->dbg_crit0, $this->dbg_crit1, $this->dbg_crit2, $this->dbg_crit3, $this->dbg_crit4, $this->dbg_crit5, /*$this->dbg_crit6,*/ $this->dbg_crit7)),
    array("name"  =>  "Memory",
          "key"   =>  "dbg_mem",
          "nodes" =>  array($this->dbg_mem0, $this->dbg_mem1)),
    array("name"  =>  "Monitoring",
          "key"   =>  "dbg_mon",
          "nodes" =>  array($this->dbg_mon0, $this->dbg_mon1)),
    array("name"  =>  "HTTP",
          "key"   =>  "dbg_http",
          "nodes" =>  array($this->dbg_http0, $this->dbg_http1, $this->dbg_http2, $this->dbg_http3)),
    array("name"  =>  "HA",
          "key"   =>  "dbg_ha",
          "nodes" =>  array($this->dbg_ha0)),
    array("name"  =>  "SSL",
          "key"   =>  "dbg_ssl",
          "nodes" =>  array($this->dbg_ssl0, $this->dbg_ssl1, $this->dbg_ssl2, $this->dbg_ssl3, $this->dbg_ssl4, $this->dbg_ssl5, $this->dbg_ssl6, $this->dbg_ssl7, $this->dbg_ssl8, $this->dbg_ssl9)),
    array("name"  =>  "NIC",
          "key"   =>  "dbg_nic",
          "nodes" =>  array($this->dbg_nic0, $this->dbg_nic1)),
    array("name"  =>  "UDP",
          "key"   =>  "dbg_udp",
          "nodes" =>  array($this->dbg_udp0)),
    array("name"  =>  "ICMP",
          "key"   =>  "dbg_icmp",
          "nodes" =>  array($this->dbg_icmp0, $this->dbg_icmp1)),
    array("name"  =>  "IP",
          "key"   =>  "dbg_ip",
          "nodes" =>  array($this->dbg_ip0, $this->dbg_ip1)),
    array("name"  =>  "TCP",
          "key"   =>  "dbg_tcp",
          "nodes" =>  array($this->dbg_tcp0, $this->dbg_tcp1, $this->dbg_tcp2)),
    array("name"  =>  "ARP",
          "key"   =>  "dbg_arp",
          "nodes" =>  array($this->dbg_arp0, $this->dbg_arp1)),
    array("name"  =>  "CSW",
          "key"   =>  "dbg_csw",
          "nodes" =>  array($this->dbg_csw0)),
    array("name"  =>  "SNMP",
          "key"   =>  "dbg_snmp",
          "nodes" =>  array($this->dbg_snmp0)),
    array("name"  =>  "CMP",
          "key"   =>  "dbg_cmp",
          "nodes" =>  array($this->dbg_cmp0)),
    array("name"  =>  "PI",
          "key"   =>  "dbg_pi",
          "nodes" =>  array($this->dbg_pi0)),
    array("name"  =>  "AUDIT",
          "key"   =>  "dbg_audit",
          "nodes" =>  array($this->dbg_audit0)),
    array("name"  =>  "LB",
          "key"   =>  "dbg_lb",
          "nodes" =>  array($this->dbg_lb0)),
    array("name"  =>  "VPN",
          "key"   =>  "dbg_vpn",
          "nodes" =>  array($this->dbg_vpn0, $this->dbg_vpn1, $this->dbg_vpn2)),
    array("name"  =>  "DNS",
          "key"   =>  "dbg_dns",
          "nodes" =>  array($this->dbg_dns0, $this->dbg_dns1, $this->dbg_dns2)),
    array("name"  =>  "Policy Engine",
          "key"   =>  "dbg_poleng",
          "nodes" =>  array($this->dbg_poleng0))
);
    }

    public function get_debug_reports()
    {
        return $this->debug_reports;
    }

    public static function get_default_report_name()
    {
        return "crit0";
    }

    public function get_report($rep_name)
    {
        try
        {
            $prop = new ReflectionProperty('debug_report_definitions', $rep_name);
            return $prop->getValue($this);
        }
        catch(Exception $e)
        {
            return null;
        }
    }
}
?>