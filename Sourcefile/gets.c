#include <stdio.h>
main()
{
	char s[80];
	fputs(fgets(s, 80, stdin), stdout);
}
