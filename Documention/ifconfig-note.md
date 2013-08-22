###ifconfig
---
ifconfig 是一个用来查看、配置、启用或禁用网络接口的工具，这个工具极为常用的。可以用这个工具来临时性的配置网卡的IP地址、掩码、广播地址、网关等。也可以把 它写入一个文件中（比如/etc/rc.d/rc.local)，这样系统引导后，会读取这个文件，为网卡设置IP地址  

语　　法：`ifconfig`[网络设备][down up -allmulti -arp -promisc][add<地址>][del<地址>][<hw<网络设备类型><硬件地址>][io\_addr<I/O地址>][irq<IRQ地址>][media<网络媒介类型>][mem\_start<内存地址>][metric<数目>][mtu<字节>][netmask<子网掩码>][tunnel<地址>][-broadcast<地址>][-pointopoint<地址>][IP地址]
	
参　　数：  

	del<地址>   删除网络设备IPv6的IP地址。  
	<hw<网络设备类型><硬件地址>   设置网络设备的类型与硬件地址。 
	irq<IRQ地址>   设置网络设备的IRQ。
	mem_start<内存地址>   设置网络设备在主内存所占用的起始地址。  
	mtu<字节>   设置网络设备的MTU。  
	tunnel<地址>   建立IPv4与IPv6之间的隧道通信地址。  
	-broadcast<地址>   将要送往指定地址的数据包当成广播数据包来处理。  
	-promisc   关闭或启动指定网络设备的promiscuous模式。  
	[网络设备]   指定网络设备的名称。

###ifconfig 配置网络接口；
---
ifconfig 可以用来配置网络接口的IP地址、掩码、网关、物理地址等；值得一说的是用ifconfig 为网卡指定IP地址，这只是用来调试网络用的，并不会更改系统关于网卡的配置文件。如果您想把网络接口的IP地址固定下来，目前有三个方法：  

+ 一是通过各个 发行和版本专用的工具来修改IP地址；  
+ 二是直接修改网络接口的配置文件；  
+ 三是修改特定的文件，加入ifconfig 指令来指定网卡的IP地址，比如在redhat或Fedora中，把ifconfig 的语名写入/etc/rc.d/rc.local文件中；

###ifconfig 配置网络端口的方法：
---
ifconfig 工具配置网络接口的方法是通过指令的参数来达到目的的，我们只说最常用的参数；  

	ifconfig 网络端口 IP地址 hw <HW> MAC地址 netmask 掩码地址 broadcast 广播地址 [up/down]＊   

	
实例二：在这个例子中，我们要学会设置网络IP地址的同时，学会设置网卡的物理地址（MAC地址）；比如我们设置网卡eth1的IP地址、网络掩码、广播地址，物理地址并且激活它；  

	[root@linuxso.com ~]# ifconfig eth1 192.168.1.252 hw ether 00:11:00:00:11:11 netmask 255.255.255.0 broadcast 192.168.1.255 up
	或
	[root@linuxso.com ~]# ifconfig eth1 hw ether 00:11:00:00:11:22

	[root@linuxso.com ~]# ifconfig eth1 192.168.1.252 netmask 255.255.255.0 broadcast 192.168.1.255 up
						其中 hw 后面所接的是网络接口类型， ether表示乙太网， 同时也支持 ax25 、ARCnet、netrom等，详情请查看 man ifconfig ；
	激活和终止网络接口的用 ifconfig 命令，后面接网络接口，然后加上 down或up参数，就可以禁止或激活相应的网络接口了。
	当然也可以用专用工具ifup和ifdown 工具；
	[root@linuxso.com ~]# ifconfig eth0 down
	[root@linuxso.com ~]# ifconfig eth0 up
	[root@linuxso.com ~]# ifup eth0
	[root@linuxso.com ~]# ifdown eth0
						  对于激活其它类型的网络接口也是如此，比如 pp0，wlan0等；不过只是对指定IP的网卡有效。
						  注意：对DHCP自动分配的IP，还得由各个发行版自带的网络工具来激活；当然得安装dhcp客户端；这个您我们应该明白；
	比如Redhat/Fedora	
	[root@linuxso.com ~]# /etc/init.d/network startSlackware 发行版；	
	[root@linuxso.com ~]# /etc/rc.d/rc.inet1
###【简 介】
---
ifconfig命令使LINUX核心知道软件回送和网卡这样一些网络接口，这样Linux就可以使用它们。除了上面介绍的这些用法之 外，ifconfig命令用来监控和改变网络接口的状态，并且还可以带上很多命令行参数。下面是一个ifconfig的通用调用语法：　　

	#ifconfig interface [[-net　-host] address [parameters]]　　

其中interface是网络接口名：address是分配给指定接口的主机名或IP地址。这里使用的主机名被解析成它们的对等IP地址，这个参数是必须的。-net和-host参数分别告诉ifconfig将这个地址作为网络号或者是主机地址。  　
　
如果调用ifconfig命令时后面只跟上端口设备名，那么它将显示这个端口的配置情况；如果不带任何参数，ifconfig命令将显示至今为止所配置的接口的所有信息；如果带上-a选项，那么还可以显示当前不活跃的接口。　　

一个检查以太网接口eth0的ifconfig调用可以得到如下的输出：　　

	#ifconfig eth0　　
					eth0 Link encap 10Mbps Ethernet HWaddr 00:00:C0:90:B3:44　　
					inet addr xxx.xxx.xxx.xxx Bcast xxx.xxx.xxx.255 Mask 255.255.255.0　　
					UP BROADCAST RUNNING MTU 1500 Metric 0　　
					RX packets 3136 errors 217 dropped 7 overrun 26　　TX packets 1752 errors 25 dropped 0 overrun 0　　
		（注意：其中XXX.XXX.XXX.XXX是IP地址）　 　
					MTU和Metric这两列显示了当前eth0接口的最大数据传送值和接口度量值。接口度量值表示在这个路径上发送一个分组的成本。
					目前内核中还没有使用路由，但可能以后会用。
					RX（接收分组数）和TX（传送分组数）这两行显示出了接收、传送分组的数目，
					以及分组出错数、丢失分组数（一个可能原因是内存 较少）和超限数（通常在接收器接收数据的速度快于核心的处理速度的时候发生）。　　
					Parameters 表示ifconfig所支持的各种参数，使用这些参数就可以便方便地监控和改变网络接口的状态。　　
	ifconfig的命令行参数:　　
					up 激活指定的接口　　
					down 关闭指定接口。该参数可以有效地阻止通过指定接口的IP信息流，
						 如果想永久地关闭一个接口，我们还需要从核心路由表中将该接口的路由信息全部删除　　
					netmask mask 为接口设置IP网络掩码。掩码可以是有前缀0x的32位十六进制数，也可以是用点分开的4个十进制数。
								 如果不打算将网络分成子网，可以不管这一选项；如果要使用子网，那么请记住，网络中每一个系统必须有相同子网掩码。　 　
					pointpoint 打开指定接口的点对点模式。它告诉核心该接口是对另一台机器的直接连接。当包含了一个地址时，这个地址被分配给列表另一端的机器。
							   如果没有给出地址，就打开这个指定接口的 POINTPOINT选项。前面加一个负号表示关闭pointpoint选项。　　
					broadcast address 当使用了一个地址时，设置这个接口的广播地址。
									  如果没有给出地址，就打开这个指定接口的IFF_BROADCAST选项。 前面加上一个负号表示关闭这个选项。　　
					metric number 将接口度量值设置为整数number。度量值表示在这个路径上发 送一个分组的成本。目前内核中还没有使用路由成本，但将来会。　　
					mtu bytes 将接口在一次传输中可以处理的最大字节数设置为整数bytes。 
							  目前核心网络代码不处理IP分段，因此一定要把MTU（最大数据 传输单元）值设置得足够大　　
					arp 打开或关闭指定接口上使用的ARP协议。前面加上一个负号用于 关闭该选项。　　
					allmuti 打开指定接口的无区别模式。打开这个模式让接口把网络上的所有信息流都送到核心中，而不仅仅是把你的机器的信息发送给核心。
						    前面加上一个负号表示关闭该选项　 　
					hw 为指定接口设置硬件地址。硬件类型名和次硬件地址对等的 ASCII字符必须跟在这个关键字后面。
					   目前支持以太网 （ether）、AMPR、AX.25和PPP traliers 打开以太网帧上的跟踪器。
					   目前还未在LINUX网络中实现，通常不需要使用所有的这些配置。　　

ifconfig可以仅由接口名、网络掩码和分配IP地址来设置所需的一切。当ifconfig疏漏了或者有一个复杂的网络时，只要重新设置大多数参数。使用netstat检查网络状态.使用netstat命令可以监控TCP/IP网络配置和工作状况。它可以显示内核路由表、 活动的网络状态以及每个网络接口的有用的统计数字。欲得详情请阅man page。  

	-a 显示所有Internet连接的有关信息，包括那些正在监听的信息　　
	-i 显示所有网络设备的统计数字　　
	-c 不断显示网络的更新状态。这个参数使用netstat每秒一次的输出网络状态列表，直到该程序被中断　　
	-n 以数字/原始形式显示远程地址、本地地址和端口信息，而不是解析主机名和服务器　　
	-o 显示计数器的终止时间和每个网络连接的回退（back off）情况　　
	-r 显示内核路由表　　
	-t 只显示TCP socket信息，包括正在监听的信息　　
	-u 只显示UDP socket信息　　
	-v 显示netstat版本信息　　
	-w 显示原始（raw）socket信息　　
	-x 显示UNIX域socket信息

`netstat -nulpn  //最常用组合`
