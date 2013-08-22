###openssl 加密命令
---

openssl是一个开源的用以实现SSL协议的产品，它主要包括了三个部分：密码算法库、应用程序、SSL协议库。Openssl实现了SSL协议所需要的大多数算法。  
下面我将单介绍使用Openssl进行文件的对称加密操作。  

####一、Openssl支持的加密算法有：  
---
	-aes-128-cbc -aes-128-cfb -aes-128-cfb1
	-aes-128-cfb8 -aes-128-ecb -aes-128-ofb
	-aes-192-cbc -aes-192-cfb -aes-192-cfb1
	-aes-192-cfb8 -aes-192-ecb -aes-192-ofb
	-aes-256-cbc -aes-256-cfb -aes-256-cfb1
	-aes-256-cfb8 -aes-256-ecb -aes-256-ofb
	-aes128 -aes192 -aes256
	-bf -bf-cbc -bf-cfb
	-bf-ecb -bf-ofb -blowfish
	-cast -cast-cbc -cast5-cbc
	-cast5-cfb -cast5-ecb -cast5-ofb
	-des -des-cbc -des-cfb
	-des-cfb1 -des-cfb8 -des-ecb
	-des-ede -des-ede-cbc -des-ede-cfb
	-des-ede-ofb -des-ede3 -des-ede3-cbc
	-des-ede3-cfb -des-ede3-ofb -des-ofb
	-des3 -desx -desx-cbc
	-rc2 -rc2-40-cbc -rc2-64-cbc
	-rc2-cbc -rc2-cfb -rc2-ecb
	-rc2-ofb -rc4 -rc4-40  

####二、Openssl加密指令语法：
---
	SYNOPSIS
	openssl enc -ciphername [-in filename] [-out filename] [-pass arg] [-e]
	[-d] [-a] [-A] [-k password] [-kfile filename] [-K key] [-iv IV] [-p]
	[-P] [-bufsize number] [-nopad] [-debug]
	说明：
	-chipername选项：加密算法，Openssl支持的算法在上面已经列出了，你只需选择其中一种算法即可实现文件加密功能。
	-in选项：输入文件，对于加密来说，输入的应该是明文文件；对于解密来说，输入的应该是加密的文件。该选项后面直接跟文件名。
	-out选项：输出文件，对于加密来说，输出的应该是加密后的文件名；对于解密来说，输出的应该是明文文件名。
	-pass选项：选择输入口令的方式，输入源可以是标准输入设备，命令行输入，文件、变量等。
	-e选项：实现加密功能（不使用-d选项的话默认是加密选项）。
	-d选项：实现解密功能。
	-a和-A选项：对文件进行BASE64编解码操作。
	-K选项：手动输入加密密钥（不使用该选项，Openssl会使用口令自动提取加密密钥）。
	-IV选项：输入初始变量（不使用该选项，Openssl会使用口令自动提取初始变量）。
	-salt选项：是否使用盐值，默认是使用的。
	-p选项：打印出加密算法使用的加密密钥。
　　　
####三、案例：
---

#####1. 使用aes-128-cbc算法加密文件：

	openssl enc -aes-128-cbc -in install.log -out enc.log
	(注：这里install.log是你想要加密的文件，enc.log是加密后的文件，回车后系统会提示你输入口令）
　　　
#####2. 解密刚刚加密的文件：
	openssl enc -d -aes-128-cbc -in enc.log -out install.log
	（注：enc.log是刚刚加密的文件，install.log是解密后的文件，-d选项实现解密功能）
　　　
#####3.加密文件后使用BASE64格式进行编码：
	openssl enc -aes-128-cbc -in install.log -out enc.log -a

#####4.使用多种口令输入方式加密：
	openssl enc -des-ede3-cbc -in install.log -out enc.log -pass pass:111111
	(这种方式的好处是你可以把它写入到脚本中，自动完成加密功能，不使用pass选项默认系统会提示输入口令，并且确认，是需要人工操作的）
　　　
####四、Openssl的功能还远不只于此，感兴趣的朋友可以参考Openssl的手册学习。在Linux系统中你可以通过：man openssl 快速获得帮助文件。
---

例：对文件file.tar.gz进行加密，密码为123456

	openssl des3 -salt -k 123456 -in file.tar.gz -out file.tar.gz.des3
	
对file.tar.gz.des3 解密  

	openssl enc -des3 -d -in file.tar.gz.des3 -out file.tar.gz
