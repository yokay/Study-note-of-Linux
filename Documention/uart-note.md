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
	MIN>0 TIME>0 : 当有MIN个字节可读，或两个输入字符间时间间隔TIME/10秒，read()函数返回。因为在输入第一个字符之后系统才启动定时器，故read()函数至少读取一个字节后返回  

原始模式:所有输入数据以字节为单位被处理，终端不可回显，所有特定的终端输入/输出控制处理不可用。通过调用cfmakeraw()函数设置为原始模式。  

	termios_p->c_iflag &= ~(IGNBRK | BRKINT PARMRK | ISTRIP | INLCR | IGNCR | ICRNL | IXON);
	termios_p->c_oflag &= ~OPOST;
	termios_p->c_lflag &= ~(ECHO | ECHONL | ICANON | ISIG | IEXTEN);
	termios_p->c_cflag &= ~(CSIZE | PARENB);
	termios_p->c_cflag |= CS8;


