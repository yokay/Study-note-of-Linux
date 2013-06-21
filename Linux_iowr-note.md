1. Linux 文件主要分为4种：```普通文件```,```目录文件```,```链接文件```,```设备文件```  
2. 对于Linux而言，所有对设备和文件的操作都是使用文件描述符来进行的。
	>文件描述符是一个非负整数，它是一个索引值，并指向在内核中每个进程打开文件的记录表。当打开一个现存文件或创建一个新文件时，内核就向进程返回一个文件描述符；当需要读写文件时，也需要把文件描述符作为参数传递给相应的函数。  
3. 一个进程启动时，会打开3个文件：```标准输入(0)```，```标准输出(1)```，```标准错误处理(2)```
4. 底层文件I/O操作的系统调用主要有5个函数：```open()```、```read()```、```write()```、```lseek()```、```close()```  
    
##函数格式：
###open()
头文件:   
`	#include <sys/types.h> `    
`	#include <sys/stat.h>`    
`	#include <fcntl.h>`     

函数原型:  
` int open(const char *pathname, int flags, int perms) `  

	pathname:被打开的文件名(可以包括路径名)

	flag:文件打开方式(flag参数可以多个组合)
			|
			|--> O_RDONLY:以只读方式打开文件
				 O_WRONLY:以只写方式打开文件
				 O_RDWR:以读写方式打开文件
				 O_CREAT:文件不存在就创建新文件，并用perms设置权限
				 O_EXCL:若使用O_CREAT时文件存在，则可返回错误信息。该参数可以测试文件是否存在。使用该参数时，open是原子操作，防止多个进程同时创建。
				 O_NOCTTY:使用该参数时，若文件为终端，则该终端不会成为调用open()的那个进程的控制终端
				 O_TRUNC:若文件已存在，则会删除文件中的全部原有数据，并设置文件大小为0。
				 O_APPEND:以添加方式打开文件，在打开文件的同时文件指针指向文件末尾，即将写入的数据添加到文件的末尾
	
	perms:被打开文件的存取权限
		  1. 宏定义:S_I(R/W/X)(USR/GRP/OTH)
		  2. 数字:0600--0000 0110 0000 0000 表示user具有rw属性。
> 成功:返回文件描述符  
  错误:返回-1  

###close()
头文件:  
` #include <unistd.h> `

函数原型:  
` int close(int fd) `  

	fd:文件描述符

> 成功:0    
  错误:-1

###read()
头文件:  
` #include <unistd.h> `  

函数原型:  
` ssize_t read(int fd, void *buf, size_t cout) `  

	fd:文件描述符  
	buf:指定存储器读出数据的缓冲区  
	count:指定读出的字节数  

> 成功:读到的字节数  
  错误:-1  
  已到达文件尾:0  

###write()
头文件:  
` #include <unistd.h> `  

函数原型:  
` ssize_t write(int fd, void *buf, size_t count) `  

	fd:文件描述符
	buf:指定存储器写入数据的缓冲区
	count:指定读出的字节数

> 成功:已写的字节数  
  错误:-1  

写普通文件时，写操作从文件的当前指针位置开始  

###lseek()
头文件:  
` #include <unistd.h>`  
` #include <sys/types.h>`

函数原型:  
` off_t lseek(int fd, off_t offset, int whence) `  

	fd:文件描述符
	offset:偏移量，每一次写操作所需要移动的距离，单位是字节，可正负

	whence:当前位置的基点  
			|
			|---> SEEK_SET:当前位置为文件开头，新位置为偏移量的大小  
				  SEEK_CUR:当前位置为文件指针的位置，新位置为当前位置+偏移量  
				  SEEK_END:当前位置为文件结尾，新位置为文件的大小+偏移量  

> 成功:文件的当前位移  
  错误:-1  

例子说明:  
	
	/* copy_file.c */  
	/* 命令格式：./cpto file1 file2 
	 * 功能：将file1的内容添加到file2的后面
	 */
	#include <unistd.h>
	#include <sys/types.h>
	#include <sys/stat.h>
	#include <fcntl.h>
	#include <stdlib.h>
	#include <stdio.h>

	#define BUFFER_SIZE 1024	/* 每次读写缓存的大小为1024byte，读写的内容为char，则是1024个char */
	int main (int argc, char *argv[])
	{
		int src_file, dest_file;	/* src_file和dest_file为文件描述符 */
		unsigned char buff[BUFFER_SIZE];	/* buff为缓存 */
		int real_read_len;	/* real_read_len为每次读取的实际字节数 */
		int offset = 0;		/* 偏移量 */

		printf("argc:%d\n",argc);	/* 显示传递的参数个数 */
		printf("The source-file is %s\n", argv[1]);		/* 显示源文件名 */

		//如果参数只有一个表示只是执行，不带参数。需要传入参数才可以执行  
		if (argc == 1)
		{
			printf("Please input source-file and dest-file.");
			exit(0);
		}
		//如果参数为2，表示./cpto file1 ，没有目标文件，
		//默认为当前目录下的source_file_temp文件，没有该文件则创建，
		//打开方式为添加到文件末尾，属性为644。
		if (argc == 2)
		{
			printf("The dest-file is ./source_file_temp\n");
			src_file = open(argv[1], O_RDONLY);
			dest_file = open("./source_file_temp", O_WRONLY | O_CREAT | O_APPEND,0644);
		}
		//参数为3，则是完整的执行方式。同样dest_file文件以添加方式打开。
		//属性为644。
		if (argc == 3)
		{
			printf("The dest-file is %s\n",argv[2]);
			src_file = open(argv[1], O_RDONLY);
			dest_file = open(argv[2], O_WRONLY | O_APPEND, 0644);
		}
		//若源文件打开错误，提示并退出。
		if (src_file < 0 )
		{
			printf("Open source-file error!\n");
			exit(1);
		}
		//若目标文件打开错误，提示并退出。
		if (dest_file < 0 )
		{
			printf("Open dest-file error!\n");
			exit(1);
		}

		//lseek将源文件的读写指针指定到文件头，偏移量为0说明不移动。
		lseek(src_file, offset, SEEK_SET);
		printf("copy src_file from %d, and offset is %d\n", SEEK_SET, offset);
	
	
		//每次读1kb，read返回读到的实际字节数，为0时表示读到文件末尾。
		//通过write存入目标文件，write写时从文件的当前指针位置开始，
		//由于打开是添加方式，即指针指在文件末尾，故每次写时是添加在文件末尾。
		while  ((real_read_len = read(src_file, buff, sizeof(buff))) > 0)
		{
			printf("sizeof(buff) is %d\nreal_read_len is :%d\n",sizeof(buff), real_read_len);
			write(dest_file, buff, real_read_len);
		}
		//关闭源文件和目标文件，通过文件描述符指定。
		close(dest_file);
		close(src_file);
		return 0;
	}
