##环境变量
###常用变量  
>PATH：系统路径  
HOME：系统根目录  
HISTSIZE：保存历史命令记录的条数  
LOGNAME：当前用户的登录名  
HOSTNAME：主机名称  
SHELL：当前用户所用的shell  
LANG：语言的环境变量  
MAIL：当前用户的邮件存放目录  
###设置变量方法  
>echo：显示字符串或环境变量  
export：设置新的环境变量  
env：显示所有环境变量  
set：显示所有本地定义的shell变量  
unset：清除环境变量  
##用户管理
>useradd：添加用户账号  
passwd：更改对应用户的密码  
例：useradd yokay
	passwd	yokay
	pwd（查看当前目录）
##进程管理
>ps：显示当前用户运行的进程  
top：动态显示运行的程序  
kill：输出特定信号给指定PID进程，一般是杀死  
unname：显示系统信息，详细的加-a  
setup：系统图形化界面配置  
crontab：执行任务命令  
uptime：显示系统运行时间  

	ps -ef[aux][w]
		|	|	|--显示加宽，并且可以显示较多的信息
		|	|------显示进程信息，包括-ef的信息、CPU、内存、进程状态等信息
		|----------查看进程信息
	kill -l[s]
		  |	|------将指定信号发送给进程
		  |----------可以列出所有可用的信号

##磁盘管理
>free：查看当前系统内存使用情况  
df：查看文件系统的磁盘空间占用情况  
du：统计目录或文件的大小  
fdisk：查看硬盘分区情况并对其管理  


	fdisk -l
		   |--------root下显示文件系统的分区  


	mount -a[l][t]
		   | |	|--------后接文件格式，vfat--fat*，ntfs-ntfs，ext*，nfs等
	 	   | |-----------列出当前已挂载的设备、文件系统和挂载点
	 	   |-------------依照/etc/fstab的内容装载所有相关的硬盘 

##文件管理
	mkdir -m[p]
		   | |-----------可以是一个路径名称，一次可建立多个目录或多级目录
	 	   |-------------后接存取权限，777表示rwxrwxrwx
	cat -n[b]
		 | |-------------和-n一样，但不对空白行编号
		 |---------------由第1行开始对所有输出的行数编号

	cp :  
		-a 保留链接、属性，并复制子目录。同-dpr一样  
		-d 复制时保留链接  
		-f 删除已经存在的目标文件且不提示  
		-i 在覆盖目标文件前提示确认  
		-p 复制源文件的内容，并把修改时间、访问权限也复制到新文件中  
		-r 若源文件是目录，则递归复制改目录下的所有子目录和文件。目标文件必须为目录  

	mv -i[f] 同上  
	rm -i[f][r] 同上  

	chown :修改文件所有者和组别  
	chgrp :改变文件的组所有权    
			-c：详尽描述每个file改变了哪些所有权  
			-f：不提示错误信息  
	
	grep :
		  -r 指定文件路径
		  -c 只输出匹配行的计数
	      -I 不区分大小写
	  	  -h 查询多文件时不显示文件名
	  	  -l 查询多文件时只输出包含匹配字符的文件名
		  -n 显示匹配行及行号
		  -s 不显示错误信息
		  -v 显示不包含匹配文本的所有行
	 
	locate :（有些系统的参数不一样）用于查找文件。方法是建立一个包括系统内所有文件名称及路径的数据库，之后寻找时只需查询数据库就行了，无需深入档案系统之中。
			-u 从根目录开始建立数据库
			-U 在指定位置开始建立数据库
			-f 将特定文件系统排除在数据库外
			-r 使用正则运算式做寻找条件
			-o 指定数据库名称

	ln -s xx xx 建立软链接，相当于快捷方式
	ln xx xx 建立硬链接，复制而来但是同步变化

	tar : 打包、解包工具
		 -c 建立新的打包文件
		 -r 向打包文件末尾追加文件
		 -x 解压
		 -o 将文件解开到标准输出
		 -v 处理过程中输出相关信息
		 -f 对普通文件操作
		 -z 压缩成gzip
		 -j 压缩成bzip2
	P.S.: -c 与 -x相对的

##常用解压命令
__``` .tar.gz/.tgz : tar xvzf ```__  
__``` .tar.bz2 : tar jxvf *** ```__

	diff : 	比较文件
			-r 对目录进行递归处理
			-q 只报告文件是否有不同，不输出结果
			-e[f] 命令格式
			-c 旧版上下文格式
			-u 新版上下文格式
	P.S.: 使用-c时，是在旧版的基础上进行比较，不同的地方用！标识。   
	*** hello1.c 。。。表示源文件  
	--- hello2.c 。。。表示新文件  
	*** 1,5 *** 表示在源文件1~5行之间  
	--- 1,5 --- 表示在新文件1~5行之间  

	使用-u时，是在新版的基础上。
	--- hello1.c 。。。表示源文件
	+++ hello2.c 。。。表示新文件
	@@ -1,5 +1,5 @@ 表示不同处在源文件的1~5行，新文件的1~5行
	-  ***   |------->表示源文件处
	+  ***   |------->表示新文件不同于源文件的地方

	P.S.: 使用-e[f]时，出现4c，表示在第4行有change。a：添加 b：删除 c：更改

	diff hello1.c hello2.c >hello.patch 可以创建补丁文件

	patch : 打补丁
			-b 生成备份文件
			-d 把目录设置为解释补丁文件名的当前目录
			-e 把输出的补丁文件当作ed脚本
			-p 去除目录名中的**/，-p后接数字如-p1
			-t 执行过程中不要求任何输出
			-v 显示patch版本号
	P.S.: patch -p3 hello1.c < /home/yokay/Desktop/hello.patch 其中hello1.c在当前目录下，-p3表示去除/home/yokay/Desktop，这样就只剩下hello.patch

##网络相关命令
>netstat ：显示网络连接、路由表和网络接口信息  
ping：查看网络上的主机是否工作  
ifconfig：查看和配置网络接口的参数  
ssh：远程登陆  

``` ifconfig -interface/up/down ```
