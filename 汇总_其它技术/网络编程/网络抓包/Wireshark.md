# Before You Begin

This release of Wireshark requires macOS 10.13 or later. If you are running an earlier version of macOS you can install using another packaging system such as Homebrew or MacPorts.

## Quick Setup

To install Wireshark, drag the ***Wireshark*** application bundle to the ***Applications*** folder.

In order to be able to capture packets, install the [Install ChmodBPF](Wireshark.app/Contents/Resources/Extras/Install%20ChmodBPF.pkg) package.

If you would like to add the path to [Wireshark](https://www.wireshark.org/docs/man-pages/wireshark.html), [TShark](https://www.wireshark.org/docs/man-pages/tshark.html), [capinfos](https://www.wireshark.org/docs/man-pages/capinfos.html), [editcap](https://www.wireshark.org/docs/man-pages/editcap.html), and [other command line utilities](https://www.wireshark.org/docs/man-pages/) to the system PATH, install the [Add Wireshark to the system path](Wireshark.app/Contents/Resources/Extras/Add%20Wireshark%20to%20the%20system%20path.pkg) package.

## Package Installation Details

The ChmodBPF installer writes to the following locations:

- /Library/LaunchDaemons/org.wireshark.ChmodBPF.plist

. A launch daemon that adjusts permissions on the system’s packet capture devices (/dev/bpf*) when the system starts up.

- /Library/Application Support/Wireshark/ChmodBPF

. A copy of the launch daemon property list, and the script that the launch daemon runs.

The installer group named “access_bpf” is created. The user who opened the package is added to the group.

The system path installer writes to the following locations:

- /etc/paths.d/Wireshark

. The folder name in this file is automatically added to PATH

- /etc/manpaths.d/Wireshark

. The folder name in this file is used by the man command.

It assumes that Wireshark is installed in ***/Applications***.

## Uninstalling

To uninstall Wireshark, do the following:

1. Remove

 ***/Applications/Wireshark.app***

1. Remove

 ***/Library/Application Support/Wireshark***

You can uninstall ChmodBPF via the [Uninstall ChmodBPF](Wireshark.app/Contents/Resources/Extras/Uninstall%20ChmodBPF.pkg) package, which is available on this disk or via **Wireshark** **›** **About Wireshark** **›** **Folders** **›** **macOS Extras**. You can also uninstall it manually by doing the following:

1. Unload the “org.wireshark.ChmodBPF.plist” launchd job

1. Remove

 ***/Library/LaunchDaemons/org.wireshark.ChmodBPF.plist***

1. Remove the “access_bpf” group.

You can uninstall the system path components via the [Remove Wireshark from the system path](Wireshark.app/Contents/Resources/Extras/Remove%20Wireshark%20from%20the%20system%20path.pkg) package, which is available on this disk or via **Wireshark** **›** **About Wireshark** **›** **Folders** **›** **macOS Extras**. You can also uninstall it manually by doing the following:

1. Remove

 ***/etc/paths.d/Wireshark***

1. Remove

 ***/etc/manpaths.d/Wireshark***