+ 当多个用户同时操作一个文件时，即共享状态下，需要给文件上锁，避免共享的资源产生竞争。Linux中实现文件上锁的函数有lockf()和fcntl()，前者施加建议锁，后则强制锁和建议锁，fcntl()还能对文件的某一记录施加记录锁(读取锁和写入锁)。  
+ 读取锁为共享锁，能够使多个进程都能在文件的同一部分建立读取锁。写入锁在任意时刻只能有一个进程在文件的某个部分上写入锁。不能同时在文件的同一部分建立读取锁和写入锁。  

头文件:  
`	#include <sys/types.h>	`  
`	#include <unistd.h>		`  
`	#include <fcntl.h>		`  

函数原型:  
` int fcntl (int fd, int cmd, struct flock *lock) `  

	fd:文件描述符

	cmd:
	 |
	 |---> F_DUPFD:复制文件描述符
	 	  F_GETFD:获得fd的close-on-exec标志，若标志为设置，则文件经过exec()函数后依然保持打开状态
		  F_SETFD:设置close-on-exec标志，该标志由参赛arg的FD_CLOEXEC位决定
		  F_GETFL:得到open设置标志
		  F_SETFL:改变open设置标志
		  F_GETLK:根据lock参数值，决定是否文件上锁
		  F_SETLK:设置lock参数值的文件锁
		  F_SETLKW:W表示wait，在无法获取琐时，进入睡眠状态，当获取锁或捕捉到信号时会返回

	lock:结构为flock，设置读取锁和写入锁的具体状态
				 |
				 |---> struct flock
				 |		{
				 |			short l_type;
				 |			off_t l_start;
				 |			short l_whence;
				 |			off_t l_len;
				 |			pid_t l_pid;
				 |		}
				 |
				 |---> l_type 	--->  + F_RDLCK:读取锁
				 			   		  + F_WRLCK:写入锁
								  	  + F_UNLCK:解锁
					   l_stat 	--->  相对位移量，字节
					   l_whence --->  + SEEK_SET:当前位置为文件开头，新位置为偏移量的大小
					   				  + SEEK_CUR:当前位置为文件指针的位置，新位置为当前位置加上偏移量
									  + SEEK_END:当前位置为文件的结尾，新位置为文件的大小加上偏移量的大小
					   l_len	--->  加锁区域的长度
				

> 成功:0  
  错误:-1  

+ 加锁整个文件的lock为:l_start=0,l_whence=SEEK_SET,l_len=0
