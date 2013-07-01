串口设置struct termios结构体成员  	

	#include <termios.h>
	struct termios
	{
		unsigned short c_iflag; 		//输入模式标志
		unsigned short c_oflag;			//输出模式标志
		unsigned short c_cflag;			//控制模式标志
		unsigned short c_lflag;			//本地模式标志
		unsigned char  c_line;			//线路规程
		unsigned char  c_cc[NCC];		//控制特性
		speed_tc_ispeed;				//输入速度
		speed_tc_ospeed;				//输出速度
	};


UNIX终端有3种工作模式，分别是规范模式(canonical mode)、非规范模式(non-canonical mode)和原始模式(raw mode)    

termios结构中的c_lflag设置ICANNON标志定义,默认为规范。  

规范模式:输入基于行，在输入行结束符(回车、EOF等)之前，系统调用read()函数读不到用户输入的任何字符。除了EOF，都会进入缓冲区中。行编辑可行，但每次调用read()函数最多只能读取一行。  

非规范模式:输入即时有效，不需要行结束符，且不可编辑。对参数MIN=c\_cc[VMIN]、TIME=c\_cc[VTIME]设置可以决定read()函数的调用方式。  
	
	MIN=0 TIME=0 : read()函数立即返回。若有可读数据，则读取数据并返回被读取的字节数，否则读取失败返回0
	MIN>0 TIME=0 : read()函数阻塞知道MIN个字节数据可被读取
	MIN=0 TIME>0 : 只要有数据可读，或TIME/10秒后，read()函数返回被读取的字节数。若超时并且未读到数据，则返回0
	MIN>0 TIME>0 : 当有MIN个字节可读，或两个输入字符间时间间隔TIME/10秒，read()函数返回。
				   因为在输入第一个字符之后系统才启动定时器，故read()函数至少读取一个字节后返回  

原始模式:所有输入数据以字节为单位被处理，终端不可回显，所有特定的终端输入/输出控制处理不可用。通过调用cfmakeraw()函数设置为原始模式。  

	termios_p->c_iflag &= ~(IGNBRK | BRKINT PARMRK | ISTRIP | INLCR | IGNCR | ICRNL | IXON);
	termios_p->c_oflag &= ~OPOST;
	termios_p->c_lflag &= ~(ECHO | ECHONL | ICANON | ISIG | IEXTEN);
	termios_p->c_cflag &= ~(CSIZE | PARENB);
	termios_p->c_cflag |= CS8;

#### c_cflag
---

	B[xxx]	xxx波特率。如B115200表示115200波特率
	EXTA	外部时钟频率
	EXTA	外部时钟频率
	CSIZE	数据位的位掩码
	CS5		5个数据位
	CS6		6个数据位
	CS7		7个数据位
	CS8		8个数据位
	CSTOPB	2个停止位，不设为1个停止位
	CREAD	接收使能
	PARENB	校验位使能
	PARODD	使用奇校验，不设使用偶校验
	HUPCL	最后关闭时挂线，放弃DTR
	CLOCAL	本地连接，不改变端口所有者
	CRTSCTS	硬件流控

#### c_iflag
---

	INPCK	奇偶校验使能
	IGNPAR	忽略奇偶校验错误
	PARMRK	奇偶校验错误掩码
	ISTRIP	裁减掉第8位比特
	IXON	启动输出软件流控
	IXOFF	启动输入软件流控
	IXANY	输入任意字符可以重新启动输出，默认为输入起始字符才重启输出
	IGNBRK	忽略输入终止条件
	BRKINT	当检测到输入终止条件时发送SIGINT信号
	INLCR	将接收到的NL(换行符)转换为CR(回车符)
	INGCR	忽略接收到的CR
	ICRNL	将接收到的CR转换为NL
	IUCLC	将接收到的大写字符映射为小写字符
	IMAXBEL	当输入队列满时响铃

#### c_oflag
---

	OPOST	启动输出处理功能，如果不设置则其他标志被忽略
	OLCUC	将输出中的大写字符转换成小写字符
	ONLCR	将输出中的NL转换成CR
	ONOCR	若当前列号为0，则不输出CR
	OCRNL	将输出中的CR转换成NL
	ONLRET	不输出CR
	OFILL	发送填充字符以提供延时
	OFDEL	填充字符为DEL字符，若不设置则为NULL字符
	NLDLY	换行延时掩码
	CRDLY	回车延时掩码
	TABDLY	制表符延时掩码
	BSDLY	水平退格符延时掩码
	VTDLY	垂直退格符延时掩码
	FFDLY	换页符延时掩码

#### c_lflag
---

	ISIG	若收到信号字符(INTR、QUIT等)，则会产生相应的信号
	ICANON	启用规范模式
	ECHO	启动本地回显功能
	ECHOE	若设置ICANON，则允许退格操作
	ECHOK	若设置ICANON，则KILL字符会删除当前行
	ECHONL	若设置ICANON，则允许回显换行符
	ECHOCTL	若设置ECHO，则控制字符(制表符、换行符等)会显示成^X，其中的X的ASCII码等于给相应控制字符的ASCII码加上0x40。如退格字符(0x08)会显示成"^H"('H'为0x48)
	ECHOPRT	若设置ICANON和ECHO，删除字符，同时显示删除的字符
	ECHOKE	若设置ICANON，回显KILL时将删除一行中的每个字符
	NOFLSH	当接收到INTR、QUIT和SUSP控制字符时，会清空输入和输出队列。若设置该标志，则所有的队列不会别清空
	TOSTOP	向试图写控制端的后台进程组发送SIGTTOU信号
	IEXTEN	启用输入处理功能

#### c_cc
---

	VINTR	中断控制字符，CTRL+C
	VQUIT	退出操作符，CTRL+Z
	VERASE	删除操作符，Backspace
	VKILL	删除行符，CTRL+U
	VEOF	文件结尾符，CTRL+D
	VEOL	附加行结尾符，CR(回车符)
	VEOL2	第二行结尾符，LF(\n)
	VMIN	指定最少读取的字节数
	VTIME	指定读取的每个字符间的超时时间
	


###1. 保存原先串口配置
---
使用tcgetattr(fd,&old_cfg)保存原先串口的配置，将fd指向的终端配置参数保存于termios结构变量old_cfg中。若调用成功返回0，否则返回-1(下面的tcgetattr、cfmakeraw、cfsetispeed等)。

	if (tcgetattr(fd,&old_cfg) !=0 )
	{
		perror("tcgetattr error");
		return -1;
	}

###2. 激活
---

	newtio.c_cflag |= CLOCAL | CREAD;	//激活本地连接和接收使能

若要设置为原始模式。

	cfmakeraw(&new_cfg);

###3. 设置波特率
---

	cfsetispeed(&new_cfg, B9600);	//设置输入波特率
	cfsetospeed(&new_cfg, B9600);	//设置输出波特率

###4. 设置字符大小
---

	new_cfg.c_cflag &= ~CSIZE;	//清除数据位中的位掩码
	new_cfg.c_cflag |= CS8;		//8位

###5. 设置奇偶校验位
---

	new_cfg.c_cflag |= (PARODD | PARENB);	//使能奇校验
	new_cfg.c_cflag |= INPCK;

	new_cfg.c_cflag |= PARENB;
	new_cfg.c_cflag &= ~PARODD;		//清除奇校验
	new_cfg.c_cflag |= INPCK;

###6. 设置停止位
---

	new_cfg.c_cflag &= ~CSTOPB;		//设置1个停止位
	new_cfg.c_cflag |= CSTOPB;		//设置2个停止位

###7. 设置最少字符和等待时间
---

	new_cfg.c_cc[VTIME] = 0;	//可以设置其它的，都设为0表示read()立即返回
	new_cfg.c_cc[VMIN] = 0;

###8. 清除串口缓冲
---

串口在重新设置后，要对当前串口设备进行适当的处理。使用tcdrain()、tcflow()、tcflush()等处理当前串口缓冲中的数据。

	int tcdrain(int fd);	//使程序阻塞，直到输出缓冲区的数据全部发送完毕
	int tcflow(int fd, int action);		//用于暂停或重新开始输出
	int tcflush(int fd, int queue_selector);	//用于清空输入/输出缓冲区
									|
									|---> TCIFLUSH  : 刷新输入队列，清空缓冲区
										  TCOFLUSH  : 刷新输出队列，清空缓冲区
										  TCIOFLUSH : 刷新输入输出队列，清空缓冲区

	tcflush(fd, TCIFLUSH);

###9. 激活配置
---

	tcsetattr(int fd, int optional_actions, const struct termios *termios_p);
								|									  |
								|									  |---> termios类型的新配置变量
								|---> TCSANOW   : 配置的修改立即生效
									  TCSDRAIN  ：配置在所有数据传输完后生效 
									  TCSAFLUSH : 配置在清空输入输出缓冲区后生效

	if ((tcsetattr(fd, TCSANOW, &new_cfg)) != 0)
	{
		perror("tcsetattr error");
		return -1;
	}
