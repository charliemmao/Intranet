use Cwd;
exit;
$oldstr = "\$SERVER\_NAME";
$oldstr = "SERVER";
$newstr = "rla.com.au";
$nofilerewrite=0;

open($fhtrack, ">F:/sub_track.html") or die "Unable to open file to write";
print $fhtrack "<html><pre>";
$nodir = 0;
$dir = cwd;
	
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
	&rewritefiles($alldirlist[$kk]);
}

open($fh, ">F:/file.txt") or die "Unable to open file to write";
	print $fh "Number of folder found\t",$#alldirlist+1,"\n";
	print $fh "Number of files found\t".$nofilectr;
	print $fh "\nNumber of files rewritten\t".$nofilerewrite;

close ($fh);

print $fhtrack "</pre></html>";

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
			} elsif ($subdir[$i] =~ m/\_private/) {
			} elsif ($subdir[$i] =~ m/\_vti_cnf/) {
			} elsif ($subdir[$i] =~ m/\_vti_pvt/) {
			} else {
				$alldirlist[$nodir] = "$thisdir$subdir[$i]";
				print $thisdir.$subdir[$i]."\n";
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

sub rewritefiles {
	###########read files from directory
	$dirfrom = shift;
	opendir(dirhdl, $dirfrom ) or die "Unable to open directory $dirfrom \n $!";
	@filelistindir= readdir dirhdl;
	closedir dirhdl;
	#print $#filelistindir."\t$dirfrom\n";
	if (length($dirfrom) > 3) {
		$dirfrom="$dirfrom/";
	}
	
	###########rewrite file
	for ($i=0; $i<=$#filelistindir; $i++) {
		$findrep = 0;
		$thisfile = $filelistindir[$i];
		$filefrom="$dirfrom$thisfile";
		if ( $thisfile =~ m/\.php/i || $thisfile =~ m/\.inc/i ) {
			$nofilectr++;
			open($fhfrom, "<$filefrom") or die "Unable to open file $filefrom\n $!";
				#print "$nofilectr\t".$thisfile ."\n";
				$linectr = 0;
				while ( <$fhfrom> ) {
					if (m/nReply\-To/gi) {
						$findrep++;
						print $fhtrack "$findrep\t".$_;
						s/\$SERVER\_NAME/rla\.com\.au/gi;
						print $fhtrack $_."\n";
					}
					$alllines[$linectr] = $_;
					$linectr++;
				}
			close($fhfrom);
	   	}

		if ($findrep) {
			$nofilerewrite++;
			print $fhtrack "File $nofilerewrite ($nofilectr)\t".$filefrom."\n<hr>\n";
			print "File $nofilerewrite ($nofilectr)\t$filefrom\n";
=com
			open($fhfrom, ">$filefrom") or die "Unable to open file $filefrom\n $!";
				for ($j=0; $j<$linectr; $j++) {
					print $fhfrom $alllines[$j];
				}
			close($fhfrom);
=cut
		}
	}
}
