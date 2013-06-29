开机后你会被提示输入您的登录名和密码，是 ` facepunch `  
按 ` Ctrl + Alt + T ` 键打开一个终端，然后执行以下命令:  

	sudo mount -o remount / 

你会被提示输入密码;输入 ` facepunch `  
然后输入以下内容：   

	sudo passwd root 

	sudo -i  （获得超级用户权限）
	fdisk -l
	mount /dev/sda7 /mnt  （也就是你的Ubuntu的“/”的挂接硬盘分区，比如sda7，根据你的具体安装情况确定，/mnt是你建立的一个用来挂接的目录）
	
如果你单独划分了Ubuntu的boot分区，那么还需要做如下操作： 

	mount /dev/sda6 /mnt/boot （假设你的boot分区是在sda6） 

挂载你其他的分区，如果有的话 重建grub到sda的mbr 

	grub-install --root-directory=/mnt /dev/sda



打开终端，输入命令

	sudo　-i

	fdisk -l (注意是字母l，不是数字1)

3、看终端出现的信息，记住自己的Ubuntu系统是装在哪个分区（如果有root分区也记下）。

4、假如你的Ubuntu的 / 分区是 sda7 又假如 /boot分区是 sda6，在终端下输入

	mount /dev/sda7 /mnt

	mount /dev/sda6 /mnt/boot （如果没 /boot 单独分区这步跳过）

	grub-install --root-directory=/mnt/ /dev/sda

	exit

5.重启

6.重启可能会出现grub选项， grub>

7.输入命令

	kernel /boot/grub/core.img

	boot

这时就可以启动Ubuntu系统了。

	update-grub


###安装Chrome OS后U盘容量变小/无法格式化解决办法 
1. 开始–运行，输入cmd，输入diskpart，回车启动DiskPart。    
2. 稍等片刻等待启动完成后，输入list disk，回车，会列出所有的磁盘（硬盘和U盘都在），看准容量（U盘必然是小的那个，注意单位哦），前面写的是Disk n（n是某个数字）。  
3. 然后再输入select disk=n（n就是前面提到的那个），回车，再输入clean，回车，OK。  
4. 接下来可以输入exit关闭DiskPart，或者直接点窗口右上角的关闭也行。  
5. 现在你的U盘就是一整块“未分配”了（全是黑色的），再用Windows的磁盘管理器新建卷并格式化就可以了。  
当然这种方式有时候也会有问题，提示error什么的，不保证一定可以解决。  