use Cwd;

$strtosearch = "";

$nodir = 0;
$dir = cwd;
$nofound=0;
open($fhhtm, ">$dir/filefound.htm") or die "Unable to open file to write";	
print $fhhtm "<html>\n";
print $fhhtm "<h1>Find string \"$strtosearch\'</h1><hr>\n<body bgcolor=\"#C0C0C0\">\n";
print $fhhtm "<b>Following files are found:</b><br><br>\n";

$alldirlist[$nodir] = $dir;
$nodir++;
&checkdir($dir);

$nodirform = 1;
$nodirto = $#alldirlist;

recursive:
for ($j=$nodirform; $j<=$nodirto; $j++) {
	#print "$j\t".$alldirlist[$j]."\n";
	&checkdir($alldirlist[$j]);
}

if ($#alldirlist > $nodirto) {
	$nodirform = $nodirto+1;
	$nodirto = $#alldirlist;
	goto recursive;
}


$nofilectr = 1;
#print $#alldirlist;
for ($kk=0; $kk<=$#alldirlist; $kk++) {
	#print "$kk\t$alldirlist[$kk]\n";
	&rewritehtml($alldirlist[$kk]);
}

print $fhhtm "</body></html><br><br>\n";
close ($fhhtm);

sub checkdir {
	$thisdir = shift;
	&findfiles ($thisdir);
	if (length($thisdir) > 3) {
		$thisdir = $thisdir."/";
	}
	for ($i=0; $i<=$#subdir; $i++) {
		if (-d "$thisdir$subdir[$i]") {
			if (($subdir[$i] =~ m/\./) and length($subdir[$i]) eq 1) {
			} elsif (($subdir[$i] =~ m/\.\./) and length($subdir[$i]) eq 2) {
			} elsif ($subdir[$i] =~ m/^\_/) {
			} elsif ($subdir[$i] =~ m/images/i) {
			} else {
				$alldirlist[$nodir] = "$thisdir$subdir[$i]";
				$nodir++;
			}
		}
	}
}
sub findfiles {
	$curdir = shift;
	opendir(dirhdl, $curdir) or die "Unable to open directory $dir\n $!";
	@subdir = readdir dirhdl;
	closedir dirhdl;
}

sub rewritehtml {
	###########read files from directory
	$dirfrom = shift;
	opendir(dirhdl, $dirfrom ) or die "Unable to open directory $dirfrom \n $!";
	@filelistindir= readdir dirhdl;
	closedir dirhdl;
	#print $#filelistindir."\t$dirfrom\n";
	if (length($dirfrom) > 3) {
		$dirfrom="$dirfrom/";
	}

	###########find string: $strtosearch  from file
outnext:
	for ($i=0; $i<=$#filelistindir; $i++) {
		$thisfile = $filelistindir[$i];
		$filefrom="$dirfrom$thisfile";
		if ($thisfile =~ m/\.php/i || $thisfile =~ m/\.inc/i) {
			if (!($thisfile =~ m/index.php/i)) {
				open($fhfrom, "<$filefrom") or die "Unable to open file $filefrom\n $!";
					while ( <$fhfrom> ) {
						if (m/$strtosearch/gi) {
							$nofound++;
							print "$nofound\n$filefrom\n";
							print "$_\n";
							print $fhhtm "$nofound&nbsp;&nbsp;";
							print $fhhtm "<a href=$filefrom>$filefrom</a><br>\n";
							print $fhhtm "$thisfile&nbsp;&nbsp;<br>\n$_<br><br>\n";
							next outnext;
						}
					}
				close($fhfrom);
			}
	   	}
	}
}
