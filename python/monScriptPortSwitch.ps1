param (
    [Parameter(Mandatory=$true)]
    [int[]]$ports,

    [Parameter(Mandatory=$true)]
    [string]$ip
    )

    function Verify-IP {
        param (
            [Parameter(Mandatory=$true)]
            [string]$ip
        )
        # Validation de l'adresse IP
        if ($ip -notmatch '^\d{1,3}(\.\d{1,3}){3}$') {
            throw "L'adresse IP '$ip' n'est pas valide. Elle doit être au format 'x.x.x.x'."
        }

        # Vérification que chaque partie de l'IP est dans la plage 0-255
        $parts = $ip -split '\.'
        foreach ($part in $parts) {
            if ([int]$part -lt 0 -or [int]$part -gt 255) {
                throw "La partie '$part' de l'adresse IP '$ip' n'est pas dans la plage 0-255."
            }
        }


        # Chack if ip respond to ping
        $ping = New-Object System.Net.NetworkInformation.Ping
        $pingReply = $ping.Send($ip)
        if ($pingReply.Status -ne 'Success') {
            throw "L'adresse IP '$ip' ne répond pas au ping."
        }
    }

    function Connexion-SNMP {
        param (
            [Parameter(Mandatory=$true)]
            [string]$ip
        )

        $SNMP = New-Object -ComObject OlePrn.OleSNMP
        $SNMP.Open($ip, "Girod", 2, 1000)
        return $SNMP
    }


    function Get-PortInfo {
        param (
            [Parameter(Mandatory=$true)]
            [object]$SNMP
        )

        $interfaces = $SNMP.GetTree('.1.3.6.1.2.1.2.2.1.8')
        $usableInterfaces = $SNMP.Get('.1.3.6.1.2.1.2.1.0')


        $infos = @()
        for($i = 0; $i -le $usableInterfaces-1; $i++) {
            $infos += [PSCustomObject]@{
                Interface = $interfaces[0,$i] -Split '\.' | Select-Object -Last 1
                Status = $interfaces[1,$i] 
            }
        }

        return $infos
    }


    function Compare-Interfaces {
        param (
            [Parameter(Mandatory=$true)]
            [object]$interfaces,

            [Parameter(Mandatory=$true)]
            [int[]]$ports
        )

        $portsNormal = $interfaces | Where-Object { $_.Interface -in $ports }
        $portsUp = $interfaces | Where-Object { $_.Status -eq 1 }
        
        # Si un port normal est down
        $portsNormalDown = $portsNormal | Where-Object { $_.Status -eq 2 }
        if ($portsNormalDown) {
            $portsNormalDown | ForEach-Object {
                Write-Host "Le port $($_.Interface) est down. Ce n'est pas normal."
            }
        }

        # Si un port up n'est pas normal
        $portsUpNotNormal = $portsUp | Where-Object { $_.Interface -notin $ports }
        if ($portsUpNotNormal) {
            $portsUpNotNormal | ForEach-Object {
                Write-Host "Le port $($_.Interface) est up mais n'est pas un port normal."
            }
        }

        # Si tout est normal
        if (-not $portsNormalDown -and -not $portsUpNotNormal) {
            Write-Host "Tous les ports sont normaux."
        }

    }


    # Vérification de l'adresse IP
    Verify-IP -ip $ip

    # Connexion SNMP
    $SNMP = Connexion-SNMP -ip $ip

    # Récupération des informations
    $interfaces = Get-PortInfo -SNMP $SNMP

    # Comparaison des ports
    Compare-Interfaces -interfaces $interfaces -ports $ports
