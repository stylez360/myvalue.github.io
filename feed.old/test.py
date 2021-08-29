import socket

import sys

#The dict module is required and is a dictionary of all CTF tokens in the form of

#tknlist = {"3" : "permission", "4": "Source ID"...}

import dict

from struct import *

from time import *



#create a socket

s= socket.socket(socket.AF_INET, socket.SOCK_STREAM)

s.setblocking(1)

#s.settimeout(3)

#connect to a server

host =     "198.190.11.31"

port = "4009"

user =     sys.argv[3] # username

password = sys.argv[4] # password

#filein = sys.argv[5] # input file name



fileout = open("out.txt", "w")



#declaration of framing characters

STX = '\x04'

ETX = '\x03'

SP  = '\x20'



#function used to format and send cmmands to the CSP

def sendcmd(cmd):

        size = len(cmd)

        size = pack('!L',size)

        s.send(STX)

        s.send(SP)

        s.send(size)

        s.send(cmd)

        s.send(ETX)

#establish the connection to the CSP

s.connect((host,port))



#prepare the login string

login =  "5022=LoginUser|5026=0|5028=marketo|5029=marketo"



sendcmd(login)



#command to select specific fields to be returned with query's to the CSP

#read = s.recv(1024)