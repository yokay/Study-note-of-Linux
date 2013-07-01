#ifndef __UART_API_H__
#define __UART_API_H__

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <termios.h>
#include <fcntl.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>

#define GNR_COM 0
#define USB_COM 1
#define COM_TYPE GNR_COM
#define BUFFER_SIZE 1024
#define HOST_COM_PORT 1
#define TARGET_COM_PORT 1
#define MAX_COM_NUM 3

int set_com_config(int fd, int baud_rate, int data_bits, char parity, int stop_bits)
{
	struct termios new_cfg, old_cfg;
	int speed;

	if (tcgetattr(fd,&old_cfg) != 0)
	{
		perror("tcgetattr error");
		return -1;
	}

	new_cfg = old_cfg;
	cfmakeraw(&new_cfg);
	new_cfg.c_cflag &= ~CSIZE;

	switch (baud_rate)
	{
		case 2400:
			{
				speed = B2400;
			}
		break;
		case 4800:
			{
				speed = B4800;
			}
		break;
		case 9600:
			{
				speed = B9600;
			}
		break;
		case 19200:
			{
				speed = B19200;
			}
		break;
		case 38400:
			{
				speed = B38400;
			}
		break;

		default:
		case 115200:
			{
				speed = B115200;
			}
		break;
	}

	cfsetispeed(&new_cfg, speed);
	cfsetospeed(&new_cfg, speed);

	switch (data_bits)
	{
		case 7:
			{
				new_cfg.c_cflag |= CS7;
			}
		break;

		default:
		case 8:
			{
				new_cfg.c_cflag |= CS8;
			}
		break;
	}
	
	switch (parity)
	{
			default:
			case 'n':
			case 'N':
				{
					new_cfg.c_cflag &= ~PARENB;
					new_cfg.c_iflag &= ~INPCK;
				}
			break;

			case 'o':
			case 'O':
				{
					new_cfg.c_cflag |= (PARODD | PARENB);
					new_cfg.c_iflag |= INPCK;
				}
			break;

			case 'e':
			case 'E':
				{
					new_cfg.c_cflag |= PARENB;
					new_cfg.c_cflag &= ~PARODD;
					new_cfg.c_iflag |= INPCK;
				}
			break;

			case 's':
			case 'S':
				{
					new_cfg.c_cflag &= ~PARENB;
					new_cfg.c_cflag &= ~CSTOPB;
				}
			break;
	}

	switch (stop_bits)
	{
			default:
			case 1:
				{
					new_cfg.c_cflag &= ~CSTOPB;
				}
			break;

			case 2:
				{
					new_cfg.c_cflag |= CSTOPB;
				}
			break;
	}

	new_cfg.c_cc[VTIME] = 0;
	new_cfg.c_cc[VMIN]  = 1;

	tcflush(fd, TCIFLUSH);

	if ((tcsetattr(fd, TCSANOW, &new_cfg)) != 0)
	{
		perror("tcsetattr error");
		return -1;
	}

	return 0;
}

int open_port(int com_port)
{
	int fd;
#if (COM_TYPE == GNR_COM)
	char *dev[] = {
					"/dev/ttyS0",
					"/dev/ttyS1",
					"/dev/ttyS2"
				  };
#else
	char *dev[] = {
					"/dev/ttyUSB0",
					"/dev/ttyUSB1",
					"/dev/ttyUSB2"
				  };
#endif

	if ((com_port < 0) || (com_port > MAX_COM_NUM))
	{
		return -1;
	}

	fd = open(dev[com_port - 1],O_RDWR | O_NOCTTY | O_NDELAY);

	if (fd < 0)
	{
		perror("open serial port ERROR");
		return -1;
	}

	if (fcntl(fd, F_SETFL, 0) < 0)
	{
		perror("fcntl F_SETFL\n");

	}

	if (isatty(STDIN_FILENO) == 0)
	{
		perror("standard input is not a terminal device");
	}

	return fd;
}
