### 多路复用
+ 阻塞I/O模型:若所调用的I/O函数没有完成相关功能，则会使进程挂起，直到相关数据到达才会返回。  
+ 非阻塞模型:当请求的I/O操作不可能完成时，则不让进程睡眠，而是立即返回。该模型下可以调用不会阻塞的I/O操作，若操作不能完成则立即返回错误或0。  
+ I/O多路转接模型:若请求的I/O操作阻塞，但不是真正阻塞I/O，而是让其中一个函数等待，则I/O还能进行其他操作。select()、poll()函数就是这样。  
+ 信号驱动I/O模型:通过安装一个信号处理程序，系统可以自动捕获特定信号的到来，从而启动I/O。由内核通知用户何时可以启动一个I/O操作决定的。  
+ 异步I/O模型:当一个描述符已准备好，可以启动I/O时，进程会通知内核。  

#### select()
头文件: 

	#include <sys/types.h>  
	#include <sys/time.h>
	#include <unistd.h>

函数原型:  

` int select(int numfds, fd_set *readfds, fd_set *writefds, fd_set *exeptfds, strcut timeval *timeout) `  

	numfds:需要监视的文件描述符的最大值+1
	readfds:读文件描述符集合
	writefds:写文件描述符集合
	exeptfds:异常处理文件描述符集合
	timeout:
		|	|---> NULL   : 永久等待，直到捕捉到信号，或文件描述符已准备好为止  
		|	|---> 具体值 : struct timeval类型的指针，若等待了timeout时间还没检测到任何文件描述符准备好，则立即返回
		|	|---> 0      : 从不等待，测试所有指定的描述符并立即返回
		|
		|---> struct timeval
				{
					long tv_sec; //s
					long tv_unsec;  ms
				}


返回值:  

	正数:成功，返回准备好的文件描述符的数目
	0   :超时
	-1  :错误

#### 文件描述符集的处理

	FD_ZERO(fd_set *set)          : 清除一个文件描述符集
	FD_SET(int fd, fd_set *set)   : 将一个文件描述符加入文件描述符集中
	FD_CLR(int fd, fd_set *set)   : 将一个文件描述符从文件描述符集中清除
	FD_ISSET(int fd, fd_set *set) : 若fd为fd_set集的一个元素，则返回非0值，可以用于调用select()之后测试文件描述符集的文件描述符是否变化

+ 在调用select()之前，要初始化文件描述符集。使用FD\_ZERO()、FD\_SET()函数。  
+ 使用select()时，可循环使用FD_ISSET()测试描述符集。  
+ 执行完对相关文件描述符的操作后，使用FD_CLR()清除描述符集。

#### poll()
头文件:

	#include <sys/tupes.h>
	#include <poll.h>

函数原型:  
` int poll(struct pollfd *fds, int numfds, int timeout) `  

	fds : struct pollfd结构的指针，用于描述需要对哪些文件的哪些类型的操作进行监控
	 |
	 |---> struct pollfd
	 		{
				int fd; //需要监听的文件描述符
		--------short events;  //需要监听的事件
		|		short revents; //已发生的事件
		|	}
		|
		|---> POLLIN   : 文件中有数据可读
			  POLLPRI  : 文件中有紧急数据可读
			  POLLOUT  : 可以向文件写入数据
			  POLLERR  : 文件中出现错误，只限于输出
			  POLLHUP  : 与文件的连接被断开，只限于输出
			  POLLNVAL : 文件描述符不合法，即它并没有指向一个成功打开的文件
	
	numfds : 需要监听的文件个数，即第一个参数所指向的数组中的元素数目
	timeout : poll阻塞的超时时间(ms)，<=0表示无限等待

返回值:  

	>0 : 成功，返回事件发生的pollfd结构的个数
	0  : 超时
	-1 : 出错
