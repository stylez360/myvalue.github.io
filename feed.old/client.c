<HTML><PLAINTEXT>
/*
 * THIS SOFTWARE IS PROVIDED BY STANDARD & POOR'S COMSTOCK ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * 
 * THE USER MUST REFER TO THE MCSP ICL MANUAL FOR ICL USAGE.
 */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <fcntl.h>

/* required for socket */
#include <errno.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <sys/socket.h>

#define TRUE    1
#define FALSE   0

#if ( WINDOWS | WIN32 | WINNT )
	#define SOCK_ERR	10000
#else
	#define SOCK_ERR	-1
#endif

char remoteHost[ 32 ];
short remotePort;
short level, logit;
FILE *logfp;

char buf[ 1024 ];
void connect_2_mcsp(char*);
short connectHost( char* servIPAddr, short servPort );
int recv_m( short fd, char *buff, int buf_suize,  int Y);

int main( int argc, char** argv )
{
	short ret = 1; 
        logit=FALSE;
	if( argc < 4 )
	{
		printf( "usage : %s remote_host_ip_address remote_port user#.dat\n", argv[ 0 ] );
		fflush( stdout );
		sleep( 3 );
		return 1;
	}

	strcpy( remoteHost, argv[ 1 ] );
	if( strchr( remoteHost, '.' ) == NULL )
	{
		printf( "Invalid IP Address \"%s\" !! Please specify IP Address ...\n",
				  remoteHost );
		return 1;
	}
	remotePort = atoi( argv[ 2 ] );

	printf( "trying to connect/close host %s at port %hd...\n", remoteHost, remotePort );
		connect_2_mcsp(argv[3]);

}

/* connect - read data for n times - close - done !! */
void connect_2_mcsp(char* filename )
{
	long count2;
	short status = 0;	 /* 0 is good, 1 if fails */
	short fd;
	short len;
	short incount=10;
	short opencount =0;
	FILE *testdata;
	char tbuf[512];
	int log = 1;
	int send_count = 0;
	int i, loop;

	testdata = fopen(filename,"rb");
	if(!testdata)
	{
		printf( "Error Opening %s \n",filename);
		return ;
	}

	memset( buf, 0, sizeof( buf ) );
	printf( "in connect\n");
	fd = connectHost( remoteHost, remotePort );
	printf( "out connect\n");
	if( fd <= 0 )
	{
		printf( "out connect failed\n");
		return ;
	}
	while(1)
	{
		memset(tbuf,0,sizeof( tbuf ));
		tbuf[0] = '\2';
		fgets(tbuf+1,sizeof(tbuf),testdata);
		if(!feof(testdata))
		{
			if(isspace(buf[1]))
				continue;

			tbuf[strlen(tbuf)-1] = '\3';
			printf( "   SENDING(%6lu): bytes: %6lu | message: %s \r\n",send_count++,strlen(tbuf), tbuf );
			send(fd,tbuf,strlen(tbuf),0);
			len = recv_m( fd, buf, sizeof( buf ) - 1, 0 );
			buf[ len ] = 0;
			printf( "   RECEIVING packet %hd\r\n", len );
			printf( " %s\r\n", buf );
			if(log)
				sleep(2);
			log = 0;
			printf( "\r\n\r\n");
		}
		else
		if(feof(testdata))
		{
			fclose(testdata);
			printf( "fclose(testdata);\r\n");
			while(1)
			{
				len = recv_m( fd, buf, sizeof( buf ) - 1, 0 );
				buf[ len ] = 0;
				printf( "\r\nreceiving packet len=%d\r\n",len );
                                i=0;
                                while (i < len)
                                {
                                 if (buf[i] < ' ')
                                   {
                                    if (buf[i] == 0x03)
                                      {
                                       printf("<ETX>\r\n");
                                       if (logit)
                                         fprintf(logfp,"<ETX>\n");
                                      }
                                    else
                                      {
                                       if (i != 0)
                                         {
				          printf( "\r\n");
                                          if (logit)
                                            fprintf(logfp,"\n");
                                         }
                              
				       printf( " [%x]", buf[i] );
                                       if (logit)
				         fprintf(logfp, " [%x]", buf[i] );
                                      }
                                   }
                                 else 
                                   {
			            printf( "%c", buf[i]);
                                    if (logit)
			              fprintf(logfp, "%c", buf[i]);
                                   }

                                 if (logit)
                                   fflush(logfp);

                                 i++;
                                }
			}
			printf( "close(fd);\r\n");
			close(fd);
                        fclose(logfp);
			return;
		}
	}
}

int recv_m( short fd, char *buf, int buf_size, int Y )
{
	int len;
	len = recv( fd, buf, buf_size, Y );
	if(len <= 0)
	{
		if(len == 0)
			printf("Server sent NULL size message %hd\n",len);
		else
			if(len < 0 )
			printf("ERROR message %hd\n",errno);
		shutdown(fd,2);
		close(fd);
		exit(0);
	}
	return len;
}


short connectHost( char* servIPAddr, short servPort )
{
	short status = 0;	 /* 0 is good, 1 if fails */
	struct sockaddr_in addr;
	short sockfd;

	memset( &addr, 0, sizeof( addr ) );
	addr.sin_family = AF_INET;
	addr.sin_addr.s_addr = inet_addr( servIPAddr );
	addr.sin_port = htons( servPort );

	if( ( sockfd = socket( AF_INET, SOCK_STREAM, 0 ) ) == SOCK_ERR )
	{
		printf( "error in socket, errno = %d\n", errno );
		return -1;
	}
	if(connect(sockfd,
				  ( struct sockaddr * ) &addr, 
				  sizeof( addr )) == SOCK_ERR)
	{
		return -1;
	}
	return sockfd;
}


