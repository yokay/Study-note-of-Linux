vm---pc---mini2440互连
---
在摆弄NFS的时候，发现centos与主机ping不通，导致NFS无法使用。后来才发现是vm没有设置好。本人所使用的配置如下：  

`VM-CentOS`  

	IP:192.168.1.111
	MASK:255.255.255.0
	GATEWAY:192.168.1.1

`PC`

	IP:192.168.1.101
	MASK:255.255.255.0
	GATEWAY:192.168.1.1
	DNS SERVER:202.114.88.10

`MINI2440`

	IP:192.168.1.70
	MASK:255.255.255.0
	GATEWAY:192.168.1.1

首先vm的网络连接方式为`Bridged`(桥接)，然后`Edit`--->`virtual network editor...`--->将桥接的物理网卡选为自己使用的(P.S.:默认是自动的，本人默认时无法ping通主机)。  **如下图所示**  

![First](https://github.com/yokay/Images/blob/master/vm-ping01.png)  
![Second](https://github.com/yokay/Images/blob/master/vm-ping02.png)



