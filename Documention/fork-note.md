###fork()
---
+ 使用fork()函数得到的子进程是父进程的一个复制品，它从父进程处继承了整个进程的地址空间，包括进程上下文、代码段、进程堆栈、内存信息、打开的文件描述符、信号控制设定、进程优先级、进程组号、当前工作目录、根目录、资源限制和控制终端等，而子进程所独有的只有它的进程号、资源使用和计时器等。  
+ 父进程中执行fork()函数时，父进程会复制出一个子进程，而且父子进程的代码从fork()函数的返回开始分别在两个地址空间中同时运行。从而两个进程分别获得其所属fork()的返回值，其中在父进程中的返回值是子进程的进程号，而在子进程中返回0。因此，可以通过返回值来判定该进程是父进程还是子进程。

所需头文件：   

	#include <sys/types.h>	//pid_t定义在此头文件中
	#include <unistd.h>

函数原型：

	pid_t fork(void)

返回值：

	 0:子进程号
	>0:父进程号
	-1:错误

###exec函数
---
ecec函数可以在一个进程中启动另一个程序执行的方法。  
所需头文件：  
` #include <unistd.h> `  
函数原型：  

	int execl(const char *path, const char *arg, ...)
	int execv(const char *path, char *const argv[])
	int execle(const char *path, const char *arg, ... , char *const envp[])
	int execve(const char *path, char *const argv[], char *const envp[])
	int ececlp(const char *file, const char *arg, ...)
	int execvp(const char *file, char *const argv[])

返回值：

	1:出错

exec[lvep]:  
	   l---list表示逐个列举参数，即参数传递为逐个列举方式   
	   v---vertor表示参数传递为构造指针数组方式  
	   e---environment表示可以传入指定的环境变量  
	   p---可执行文件查找方式为文件名  

__所有参数以NULL结束__  

使用例子：  

	execlp("ps", "ps", "-ef", NULL);	//在$PATH下查找ps，执行ps -ef
	execl("/bin/ps", "ps", "-ef", NULL);	//查找/bin/ps，并执行ps -ef

	char *envp[] = {"PATH = /tmp", "USER = yokay", NULL};
	execle("/usr/bin/env", "env", NULL, envp);	//查找/usr/bin/env，执行env，环境变量为PATH=/tmp，USER=yokay

	char *arg[] = {"env", NULL};
	char *envp[] = {"PATH = /tmp", "USER = yokay", NULL);
	execve("/usr/bin/env", arg, evnp);	//查找/usr/bin/env，执行arg内的命令，环境变量由envp指定

__exec函数使用时要检查错误__  
常见的有：
> 找不到文件或路径，errno为ENOENT  
> 数组argv、envp未以NULL结束，errno为EFAULT  
> 没有对应可执行文件的运行权限，errno为FACCES  


###exit()、_exit()
_exit()直接使进程停止运行，清除其使用的内存空间和在内核中的各种数据结构。  
exit()在停止前检查文件的打开状况，并把文件缓冲区的内容写回文件。  

例：  

	printf("Using exit...\n");
	printf("This is ...");
	exit(0);	
	/* 
	2个printf都有输出。若用_exit(0)则只有第一个输出。
	因为printf在遇到"\n"时自动读取缓冲区内容，即执行第一个printf时，第二个的内容在缓冲区中，	
	exit(0)等缓冲区输出后退出，而_exit(0)则不会管缓冲区。
	*/

__当进程调用exit()后，进程变成Zombie，不再占有任何内存空间__  

###wait()、waitpid()
---
wait()函数用于使父进程阻塞，直到子进程结束或进程接到指定信号。若该父进程没有子进程或其子进程已经结束，则wait()会立即返回。  

所需头文件：  

	#include <sys/types.h>
	#include <sys/wait.h>
	
函数原型：  
` pid_t wait(int *status) `  
status指向子进程退出时的状态  

返回值：  
	已结束的子进程号:成功  
	              1:失败  
	              
函数原型：  
` pid_t waitpid(pid_t pid, int *tatus, int options) `  

	pid : >0 若指定的子进程没结束，则一直wait
		  =1 等待任何一个子进程退出，同wait()
		  =0 等待其组ID为调用进程组ID的子进程
		  <1 等待其组ID为pid的绝对值的任一子进程
		  
	status : 同上
	
	options : WNOHANG--->若pid指定的子进程不立即可用，则waitpid()不阻塞，返回0
			  WUNTRACED--->【？？？？？】
			  0--->阻塞父进程，等待子进程退出
			  
返回值：  

	已经结束的子进程号:正常
	0:使用WNOHANG，没有子进程退出
	1:错误
