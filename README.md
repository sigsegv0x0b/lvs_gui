# lvs_gui
messing around with a trivial LVS gui

php user needs to be able to run ipvsadm as root to edit rules and ipvsadm init script to save them

ex.
www-data	ALL=(root) NOPASSWD:	/sbin/ipvsadm
www-data	ALL=(root) NOPASSWD:	/etc/init.d/ipvsadm
