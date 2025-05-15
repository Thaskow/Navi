from pysnmp.hlapi import *

from pysnmp.hlapi import *

class SNMPClient:
    def __init__(self, target, community):
        self.target = target
        self.community = community

    def snmp_walk(self, oid):
        iterator = nextCmd(
            SnmpEngine(),
            CommunityData(self.community),
            UdpTransportTarget((self.target, 161)),
            ContextData(),
            ObjectType(ObjectIdentity(oid)),
            lexicographicMode=False
        )

        response = []
        for errorIndication, errorStatus, errorIndex, varBinds in iterator:
            if errorIndication:
                print(f'Error: {errorIndication}')
                break
            elif errorStatus:
                print(f'Error: {errorStatus.prettyPrint()} at {errorIndex and varBinds[int(errorIndex) - 1][0] or "?"}')
                break
            else:
                for varBind in varBinds:
                    response.append([varBind[0].prettyPrint() , varBind[1].prettyPrint()])
                    # Convert varbind[1] into mac adress
        return response


    def snmp_get(self, oid):
        iterator = getCmd(
            SnmpEngine(),
            CommunityData(self.community),
            UdpTransportTarget((self.target, 161)),
            ContextData(),
            ObjectType(ObjectIdentity(oid))
        )

        for errorIndication, errorStatus, errorIndex, varBinds in iterator:
            if errorIndication:
                print(f'Error: {errorIndication}')
                break
            elif errorStatus:
                print(f'Error: {errorStatus.prettyPrint()} at {errorIndex and varBinds[int(errorIndex) - 1][0] or "?"}')
                break
            else:
                for varBind in varBinds:
                    return varBind[1].prettyPrint()


